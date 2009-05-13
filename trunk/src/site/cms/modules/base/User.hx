/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base;
import poko.form.elements.Button;
import poko.form.elements.CheckboxGroup;
import poko.form.elements.Hidden;
import poko.form.elements.Input;
import poko.form.elements.RadioGroup;
import poko.form.Form;
import poko.utils.Tools;
import haxe.Md5;
import php.Web;
import site.cms.modules.base.Users;

class User extends UsersBase
{
	public var userData:List<Dynamic>;
	public var action:String;
	public var actionId:Int;
	
	public var form1:Form;
	public var heading:String;
	
	public function new() 
	{
		super();
		authenticationRequired = ["cms_admin", "cms_manager"];
	}
	
	override public function main():Void
	{	
		action = application.params.get("action");
		actionId = Std.parseInt(application.params.get("id"));
		
		if (action != "edit" && action != "add") action = "add";
		heading = (action == "edit") ? "Edit User" : "Add User";
		
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
				var d:Dynamic = form1.getData();
				var exists = application.db.exists("_users", "`username`=\""+d.username+"\"");
				
				if(!exists){
					try {	
						var s = Web.getParamValues(form1.name + "_groups");
						var a = (s != null) ? s.join(",") : "";
						d.groups = a;
						d.password = Md5.encode(d.password);
						d.added = Date.now();
						application.db.insert("_users", d);
					}catch (e:Dynamic) {
						if (application.debug) throw(e);
						application.messages.addError("Database error.");
					}
					if (application.db.lastAffectedRows < 1) {
						application.messages.addError("Problem adding user.");
					}else {
						application.messages.addMessage("User added. <a href=\"?request=cms.modules.base.User&action=edit&id=" + application.db.cnx.lastInsertId() + "\">edit</a>");
						form1.clearData();
					}
				} else {
					application.messages.addError("User '"+d.username+"' aready exists");
				}
			case "edit":
				var d:Dynamic = form1.getData();
				
				try {	
					var s = Web.getParamValues(form1.name + "_groups");
					var a = (s != null) ? s.join(",") : "";
					d.groups = a;
					var oldPassword = application.db.requestSingle("SELECT password FROM `_users` WHERE id=" + actionId).password;
					if (d.password != oldPassword)
						d.password = Md5.encode(d.password);
					
					application.db.update("_users", d, "id="+form1.getElement("actionId").value);
				}catch (e:Dynamic) {
					if (application.db.exists("_users", "`username`=\"" + d.username + "\""))
					{
						application.messages.addError("Another user '"+d.username+"' already exists");
					} else {
						application.messages.addError("Database error.");
					}
				}
				if (application.db.lastAffectedRows < 1) {
					application.messages.addWarning("Nothing changed.");
				}else {
					application.messages.addMessage("User updated.");
				}
		}
	}
	
	private function setupForm1():Void
	{
		var userInfo:Dynamic = { };
		var groupsSelected:Array<String> = new Array();
		
		var groups:List<Dynamic>;
		var sql:String;
		
		if(!application.user.isSuper()){
			sql = "SELECT `stub` AS 'key', `name` AS 'value' FROM _users_groups WHERE isAdmin=0 AND isSuper=0";
			groups = application.db.request(sql);
		}else {
			// super, they can do anything!
			sql = "SELECT `stub` AS 'key', `name` AS 'value' FROM _users_groups";
			groups = application.db.request(sql);
		}
		
		if (action == "edit") {
			userInfo = application.db.requestSingle("SELECT * FROM `_users` WHERE `id`=" + actionId);
			groupsSelected = userInfo.groups.split(",");
		}
		
		form1 = new Form("form1");
		form1.addElement(new Hidden("actionId", actionId));
		form1.addElement(new Input("username", "Username", userInfo.username, true));
		var pass = new Input("password", "Password", userInfo.password, true);
		pass.password = true;
		form1.addElement(pass);
		
		form1.addElement(new Input("name", "Name", userInfo.name, true));
		form1.addElement(new Input("email", "Email", userInfo.email, true));
		form1.addElement(new CheckboxGroup("groups", "Group", groups, groupsSelected));
		form1.addElement(new Button("submit", "Update", (action == "edit") ? "Update" : "Add"));
		
		form1.populateElements();
	}
}