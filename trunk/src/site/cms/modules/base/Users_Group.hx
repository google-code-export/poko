/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base;
import poko.form.elements.Button;
import poko.form.elements.CheckboxGroup;
import poko.form.elements.Hidden;
import poko.form.elements.Input;
import poko.form.Form;
import poko.utils.PhpTools;
import php.Web;
import site.cms.modules.base.Users;

class Users_Group extends UsersBase
{
	public var userData:List<Dynamic>;
	public var action:String;
	public var actionId:Int;
	
	public var form1:Form;
	public var heading:String;
	
	public function new() 
	{
		authenticationRequired = ["cms_admin"];
		super();
	}
	
	override public function main():Void
	{	
		action = application.params.get("action");
		actionId = Std.parseInt(application.params.get("id"));
		
		if (action != "edit" && action != "add") action = "add";
		heading = (action == "edit") ? "Edit Group" : "Add Group";
		
		setupForm1();
		if (form1.isSubmitted() && form1.isValid())
			process();
		
		super.main();
	}
	
	private function process():Void
	{
		switch(action)
		{
			case "add":
				try {	
					var d:Dynamic = form1.getData();
					var a = Web.getParamValues(form1.name + "_permissions");
					d.isAdmin = '0';
					d.isSuper = '0';
					if (a != null) {
						for(s in a)
							Reflect.setField(d, s, '1');
					}
					application.db.insert("_users_groups", d);
				}catch (e:Dynamic) {
					application.messages.addError("Database error.");
				}
				if (application.db.lastAffectedRows < 1) {
					application.messages.addError("Problem adding group.");
				}else {
					application.messages.addMessage("Group added. <a href=\"?request=cms.modules.base.Users_Group&action=edit&id="+application.db.cnx.lastInsertId()+"\">edit</a>");
					form1.clearData();
				}
			case "edit":
				try {
					var d:Dynamic = form1.getData();
					var a = Web.getParamValues(form1.name + "_permissions");
					d.isAdmin = '0';
					d.isSuper = '0';
					if (a != null) {
						for(s in a)
							Reflect.setField(d, s, '1');
					}
					application.db.update("_users_groups", d, "id=" + form1.getElement("actionId").value);
				}catch (e:Dynamic) {
					application.messages.addError("Database error.");
				}
				if (application.db.lastAffectedRows < 1) {
					application.messages.addWarning("Nothing changed.");
				}else {
					application.messages.addMessage("Group updated.");
				}
		}
	}
	
	private function setupForm1():Void
	{
		var groupInfo:Dynamic = { };
		var permissionsListSelected:Array<String> = new Array();
		
		if (action == "edit") {
			groupInfo = application.db.requestSingle("SELECT * FROM `_users_groups` WHERE `id`=" + actionId);
			if (groupInfo.isAdmin) permissionsListSelected.push('isAdmin');
			if (groupInfo.isSuper) permissionsListSelected.push('isSuper');
		}
		
		var permissionsList:List<Dynamic> = new List();
		permissionsList.add( { key:'isAdmin', value:"Admin" } );
		permissionsList.add( { key:'isSuper', value:"Super" } );
		
		form1 = new Form("form1");
		form1.addElement(new Hidden("actionId", actionId));
		form1.addElement(new Input("stub", "Stub", groupInfo.stub, true));
		form1.addElement(new Input("name", "Name", groupInfo.name, true));
		form1.addElement(new Input("description", "Description", groupInfo.description, true));
		form1.addElement(new CheckboxGroup("permissions", "Permissions", permissionsList, permissionsListSelected));
		form1.addElement(new Button("submit", "Update", (action == "edit") ? "Update" : "Add"));
		form1.populateElements();

		form1.setSubmitButton(form1.getElement("submit") );
	}	
}