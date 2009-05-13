/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.modules.base;
import site.cms.templates.CmsPopup;
import site.cms.components.DbStructureSelector;


class DbStructure extends CmsPopup
{
	public var dbStructureSelector:DbStructureSelector;
	
	var fields:List<Dynamic>;
	
	override public function main()
	{
		head.css.add("css/cms/popup.css");
		
		dbStructureSelector = new DbStructureSelector(false);
		
		var table = application.params.get("dbStructureSelector");
		fields = application.db.request("SHOW FIELDS FROM `" + table + "`");
	}
	
}