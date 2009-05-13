/**
 * ...
 * @author ...
 */

package site.cms.modules.base;
import poko.Application;
import poko.TemploObject;
import poko.utils.JsBinding;
import site.cms.templates.CmsTemplate;


class Pages extends PageBase
{
	
	public function new() 
	{
		super();
	}
	
	override public function pre()
	{		
	}
	
	
	override public function main()
	{
		super.main();
		
		if (!application.params.get("manage"))
		{
			var str = "< Select a Page";
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				str += TemploObject.parse("site/cms/modules/base/blocks/pages.mtt");
				
			setContentOutput(str);
			
			setupLeftNav();
			return;
		}	
		
		// Managment is done in the definitions page
	}
}


class PageBase extends CmsTemplate
{
	public var pages:List<Dynamic>;
	
	override public function pre()
	{
		navigation.setSelected("Pages");
	}
	
	public function setupLeftNav():Void
	{	
		super.main();
		
		pages = application.db.request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`" );
		
		leftNavigation.addSection("Pages");
		
		for (page in pages)
		{
			leftNavigation.addLink("Pages", page.name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id="+page.pid, page.indents);
		}
		
		if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
			leftNavigation.footer = "<br /><a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a><br />";
	}
	
}
