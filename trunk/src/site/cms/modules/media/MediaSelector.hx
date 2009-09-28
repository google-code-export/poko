/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.modules.media;
import php.FileSystem;
import php.Web;
import poko.form.elements.Button;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.js.JsBinding;
import site.cms.templates.CmsPopup;
import site.cms.templates.CmsTemplate;

class MediaSelector extends CmsPopup
{
	public var elementId:String;
	public var form:Form;
	public var selector:Selectbox;
	public var items:List<String>;
	private var gallery:String;
	var jsBind:JsBinding;
	
	override public function main()
	{	
		elementId = application.params.get("elementId");		
		
		head.css.add("css/cms/media.css");
		head.css.add("css/cms/popup.css");
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDatasetItem");
		
		var exclude = [".", "..", ".svn"];
		var imageRoot = "./res/media/galleries";
		var galleries = new List();
		var dir = php.FileSystem.readDirectory(imageRoot);
		for (d in dir)
		{
			if (FileSystem.isDirectory(imageRoot + "/" +d) && !Lambda.has(exclude, d)) 
			{
				galleries.add( { key:d, value:d } );
			}
		}
		var selector = new Selectbox("gallery", "Select Gallery", galleries);
		
		form = new Form("form1");
		form.addElement(selector);
		form.setSubmitButton(form.addElement(new Button("submit", "submit")));
		form.populateElements();
		
		gallery= application.params.get("form1_gallery");
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
}