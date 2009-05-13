/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base;

import php.Web;
import site.cms.templates.CmsTemplate;

class Users extends UsersBase
{
	public var users:List<Dynamic>;	
	public var action:String;
	public var actionId:Int;
	
	public function new() 
	{
		super();
		authenticationRequired = ["cms_admin", "cms_manager"];
	}
	
	override public function main():Void
	{			
		super.main();
		
		action = application.params.get("action");
		actionId = Std.parseInt(application.params.get("id"));
		
		if (action == "delete") {
			if (actionId == null) {
				application.messages.addError("Trying to delete, no ID given.");
			}else {
				try{
					application.db.delete("_users", "id=" + actionId);
				}catch (e:Dynamic) {
					application.messages.addError("Can't delete user.");
				}
				if (application.db.lastAffectedRows > 0) {
					application.messages.addMessage("User deleted.");
				}else {
					application.messages.addWarning("No user to delete.");
				}
			}
		}
		
		setContentTemplate("cms/modules/base/Users.mtt");
		users = application.db.request("SELECT * FROM _users");
	}
}

class UsersBase extends CmsTemplate
{
	override public function main()
	{
		super.main();		
		
		navigation.setSelected("Users");
		
		setupLeftNav();
	}
	
	private function setupLeftNav():Void
	{
		// build the nav
		leftNavigation.addSection("Users");
		leftNavigation.addLink("Users", "List Users", "cms.modules.base.Users&action=list");
		leftNavigation.addLink("Users", "Add User", "cms.modules.base.User&action=add");
		
		if(application.user.isSuper()){
			leftNavigation.addSection("Groups");
			leftNavigation.addLink("Groups", "List Groups", "cms.modules.base.Users_Groups&action=list");
			leftNavigation.addLink("Groups", "Add Group", "cms.modules.base.Users_Group&action=add");
		}
	}
}
