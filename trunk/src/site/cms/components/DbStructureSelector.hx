/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.components;
import poko.Application;
import poko.Component;
import poko.form.elements.Button;
import poko.form.elements.Selectbox;
import site.cms.common.Tools;
import poko.form.Form;

class DbStructureSelector extends Component
{
	public var popup:Bool;
	private var selector:Selectbox;
	
	public function new(popup:Bool=true) 
	{
		super();
		
		this.popup = popup;
	}
	
	override public function main():Void
	{
		var tables = Tools.getDBTables();
		var application = Application.instance;
		
		application.request.head.css.add("css/cms/jquery.windows-engine.css");
		application.request.head.js.add("js/cms/jquery.windows-engine.js");
		
		var dbStructureSelector = application.params.get("dbStructureSelector");
		selector = new Selectbox("selector", "selector", null, dbStructureSelector, false, (popup ? null : ""));
		
		for (table in tables)
			selector.addOption( { key:table, value:table } );
		
		if (popup) {
			selector.onChange = "if(this.value != '') launchWindow(this.value)";
		} else {
			selector.onChange = "if(this.value != '') window.location = '?request=cms.modules.base.DbStructure&dbStructureSelector='+this.value";
		}
	}
}