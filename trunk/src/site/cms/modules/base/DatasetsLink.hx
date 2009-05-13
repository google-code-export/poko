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
import site.cms.templates.CmsTemplate;
import site.cms.modules.base.Datasets;

class DatasetsLink extends DatasetBase
{
	private var tableName:String;
	private var form1:Form;
	
	public function new() 
	{
		super();
	}
	
	override public function main()
	{	
		tableName= application.params.get("tableName");
		
		var info = application.db.requestSingle("SELECT t.*, d.`name` as 'definitionName' FROM `_datalink` t, `_definitions` d WHERE t.definitionId=d.id AND t.`tableName`=\"" + tableName + "\"");
			
		// Setup form
		form1= new Form("form1");
		form1.addElement(new Input("label", "Label", info.label));
		form1.addElement(new Selectbox("definitionId", "Definition", null, info.definitionId, false, "- none -" ));
		form1.addElement(new Selectbox("indents", "Indents", null, info.indents, false, "- none -" ));
		form1.addElement(new Button("submit", "Update"));
		form1.populateElements();
		
		// process actions
		if (form1.isSubmitted()) process();
		
		// fill in form data
		var definitionsSelctor = form1.getElementTyped("definitionId", Selectbox);
		definitionsSelctor.data = application.db.request("SELECT `name` as 'key', `id` as 'value' FROM _definitions WHERE isPage='0'");
		
		var indentSelctor = form1.getElementTyped("indents", Selectbox);
		indentSelctor.addOption( { key:1, value:1 } );
		indentSelctor.addOption( { key:2, value:2 } );
		indentSelctor.addOption( { key:3, value:3 } );
		indentSelctor.addOption( { key:4, value:4 } );

		setupLeftNav();
	}
	
	private function process():Void
	{
		application.db.update("_datalink", form1.getData(), "`tableName`='" + tableName + "'");
		
		application.messages.addMessage("DataLink '" + tableName + "' has been updated");
		
		application.redirect("?request=cms.modules.base.Datasets&manage=true");
	}
	
}