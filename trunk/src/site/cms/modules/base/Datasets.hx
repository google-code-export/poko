/**
 * ...
 * @author ...
 */

package site.cms.modules.base;

import poko.Application;
import poko.form.elements.Button;
import poko.form.elements.Input;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.form.FormElement;
import poko.Request;
import poko.TemploObject;
import php.Web;
import site.cms.common.Tools;
import site.cms.templates.CmsTemplate;

class Datasets extends DatasetBase
{
	public var data:List<Dynamic>;
	
	public function new() 
	{
		super();
	}
	
	override public function main()
	{	
		if (application.params.get("manage") == null)
		{
			var str = "< Select a Dataset";
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				str += TemploObject.parse("site/cms/modules/base/blocks/datasets.mtt",{});
			
			setContentOutput(str);
			
			setupLeftNav();
			return;
		}
		
		// Managment is done in the definitions page
	}	
}

class DatasetBase extends CmsTemplate
{
	public var pagesMode:Bool;
	public var linkMode:Bool;
	
	override public function pre()
	{
		if (application.params.get("manage") != null) {
			authenticationRequired = ["cms_admin", "cms_manager"];
		}
		
		linkMode = application.params.get("linkMode") == "true";
		pagesMode = application.params.get("pagesMode") == "true";
		
		if (pagesMode) 
		{
			navigation.pageHeading = "Pages";
			navigation.setSelected("Pages");
		} 
		else 
		{
			navigation.pageHeading = "Datasets"; 
			navigation.setSelected("Datasets");
		}
	}
	
	private function setupLeftNav():Void
	{
		if (pagesMode) 
		{
			var pages = application.db.request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			
			leftNavigation.addSection("Pages");

			for (page in pages) {
				leftNavigation.addLink("Pages", page.name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" + page.pid, page.indents);
			}
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				leftNavigation.footer = "<br /><a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a><br />";
		} 
		else 
		{
			var tables:List <Dynamic> = application.db.request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
			
			// build the nav
			leftNavigation.addSection("Datasets");
			
			var def:Dynamic = Definition;
			for (table in tables)
			{
				if(table.showInMenu){
					var name = table.name != "" ? table.name : table.table;
					leftNavigation.addLink("Datasets", name, "cms.modules.base.Dataset&dataset=" + table.id, table.indents);
				}
			}
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				leftNavigation.footer = "<br /><a href=\"?request=cms.modules.base.Definitions&manage=true\">Manage</a><br />";			
		}
	}
}
