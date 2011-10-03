/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.modules.media;
import haxe.Md5;
import php.FileSystem;
import php.io.File;
import php.Lib;
import php.Session;
import php.Web;
import poko.form.elements.Button;
import site.cms.modules.base.formElements.FileUpload;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.js.JsBinding;
import poko.utils.PhpTools;
import site.cms.templates.CmsPopup;
import site.cms.templates.CmsTemplate;

class MediaSelector extends CmsPopup
{
	public var elementId:String;
	public var form:Form;
	public var uploadForm:Form;
	public var selector:Selectbox;
	public var items:List<String>;
	private var gallery:String;
	public var jsBind:JsBinding;
	public var jsBindTop:JsBinding;
	
	public static var FROM_CMS:String = "cms";
	public static var FROM_TINYMCE:String = "tinyMce";
	public static var FROM_WYM:String = "wym";
	
	public var from:String;
	public var baseUrl:String;
	
	public var currentView:String;
	public static var VIEW_LIST:String = "list";
	public static var VIEW_THUMBS:String = "thumbs";
	
	public var showOnlyLibraries:Array<String>;
	public var allowViewThumb:Bool;
	public var allowViewList:Bool;
	
	override public function init()
	{
		super.init();
		
		if (app.params.get("showOnlyLibraries") != null && app.params.get("showOnlyLibraries") != "") {
			showOnlyLibraries = app.params.get("showOnlyLibraries").split(":");
		}else {
			showOnlyLibraries = [];
		}
		
		from = FROM_CMS;
		if (app.params.get("from") != null) from = app.params.get("from");
		
		gallery = app.params.get("form1_galleryList");
		if (showOnlyLibraries.length == 1) gallery = showOnlyLibraries[0];
		if (gallery == null) gallery = Session.get('mediaGalleryLastGallery');
		if (gallery != null) Session.set('mediaGalleryLastGallery', gallery);
		
		allowViewThumb = (app.params.get("libraryViewThumb") == "1");
		allowViewList = (app.params.get("libraryViewList") == "1");
		
		if (from == FROM_WYM) {
			allowViewList = false;
			allowViewThumb = true;
			currentView = VIEW_THUMBS;
		}
	}
	
	override public function main()
	{	
		elementId = app.params.get("elementId");
		
		if (app.params.get("viewType") != null)
		{
			currentView = app.params.get("viewType");
		}else {
			if (Session.get("mediaGalleryCurrentView") != null) {
				currentView = Session.get("mediaGalleryCurrentView");
			}else {
				currentView = VIEW_THUMBS;
			}
		}
		Session.set("mediaGalleryCurrentView", currentView);
		if (currentView == VIEW_LIST && allowViewList == false) currentView = VIEW_THUMBS;
		if (currentView == VIEW_THUMBS && allowViewThumb == false) currentView = VIEW_LIST;
		
		baseUrl = "http://" + Web.getHostName() + Web.getURI();
		
		head.css.add("css/cms/media.css");
		head.css.add("css/cms/popup.css");
		
		head.js.add("js/cms/jquery.qtip.min.js");
		if (from == FROM_TINYMCE) {
			head.js.add("js/cms/tiny_mce/tiny_mce_popup.js");
			head.js.add("js/cms/tiny_mce_browser.js");
		}
		
		jsBind = new JsBinding("site.cms.modules.media.js.JsMediaSelector");
		jsBindTop = new JsBinding("site.cms.modules.base.js.JsDatasetItem");
		
		var exclude = [".", "..", ".svn"];
		var imageRoot = "./res/media/galleries";
		var galleries = new List();
		var dir = php.FileSystem.readDirectory(imageRoot);
		for (d in dir)
		{
			if (FileSystem.isDirectory(imageRoot + "/" +d) && !Lambda.has(exclude, d))
			{
				if(showOnlyLibraries.length == 0 || Lambda.has(showOnlyLibraries, d))
					galleries.add( { key:d, value:d } );
			}
		}

		var selector = new Selectbox("galleryList", "Select Gallery", galleries, gallery);
		
		form = new Form("form1");
		form.addElement(selector);
		form.setSubmitButton(new Button("submit", "submit"));
		form.populateElements();
		
		// image upload
		if (gallery != ""){
			uploadForm = new Form('uploadForm');
			var el = new FileUpload('file', 'File Upload');
			el.showLibrary = false;
			uploadForm.addElement(el);
			uploadForm.addElement(new Button('submit', 'Add file'));
			
			if (uploadForm.isSubmitted()) {
				// add new files
				var files:Hash <Hash<Dynamic>> = PhpTools.getFilesInfo();
				// data to insert into DB
				var data:Hash<String> = new Hash();
				
				for (file in files.keys())
				{
					var info:Hash<Dynamic> = files.get(file);
					var name = file.substr(form.name.length + 1);
					var filename = info.get("name");
					var randomString = Md5.encode(Date.now().toString()+Math.random());
					
					// upload file	
					if (info.get("error") == 0) 
					{
						PhpTools.moveFile(info.get("tmp_name"), './res/media/galleries/' + gallery + '/' + randomString + filename);
						data.set(name, randomString + filename);
					}
				}
			}
		}
		
		items = new List();
		
		if(gallery != null && gallery != "")
		{
			var dir = php.FileSystem.readDirectory(imageRoot + "/" + gallery);
			for (d in dir)
			{
				if (!Lambda.has(exclude, d))
				{
					items.add(d);
				}
			}
		}
	}
	
	public function getItem(name:String)
	{
		var onClick = switch(from)
		{
			case FROM_CMS:
				"window.top."+jsBindTop.getCall('updateItemFromMedia', [elementId, gallery, name]);
			case FROM_TINYMCE:
				"updateTinyMceWithValue('/res/media/galleries/" + gallery +"/" + name + "');";
			case FROM_WYM:
				"opener.document.getElementById('filebrowser').value = '"+baseUrl+"res/media/galleries/" + gallery +"/" + name + "'; window.close();";
		}
		
		var ext = name.substr(name.lastIndexOf(".") + 1).toLowerCase();
		var str = "";
		if(currentView == VIEW_THUMBS){
			switch(ext) {
				case "jpg", "gif", "png":
					str += '<img class="qTip" title="File: '+name+'" src="?request=cms.services.Image&preset=thumb&src=../media/galleries/'+gallery+'/'+name+'" onClick="'+onClick+'" />';
				case "txt":
					str += '<img class="qTip" title="File: '+name+'" src="./res/cms/media/file_txt.png" onClick="'+onClick+'" />';
				case "pdf":
					str += '<img class="qTip" title="File: '+name+'" src="./res/cms/media/file_pdf.png" onClick="'+onClick+'" />';
				default:
					str += '<img class="qTip" title="File: '+name+'" src="./res/cms/media/file_misc.png" onClick="'+onClick+'" />';
			}
		}else {
			str += '<div onClick="'+onClick+'">'+name+'</div>';
		}
		return str;
	}
}
