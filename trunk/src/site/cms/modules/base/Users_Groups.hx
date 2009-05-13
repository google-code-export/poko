/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base;
import php.Web;
import site.cms.modules.base.Users;

class Users_Groups extends UsersBase
{
	public var groups:List<Dynamic>;	
	public var action:String;
	public var actionId:Int;
	
	public function new() 
	{
		authenticationRequired = ["cms_admin"];
		super();
	}
	
	override public function main():Void
	{
		super.main();
		
		action = application.params.get("action");
		actionId = Std.parseInt(application.params.get("id"));
		
		if (action == "delete") {
			if (actionId == null) {
				application.messages.addError("Trying to delete group, no ID given.");
			}else {
				try{
					application.db.delete("_users_groups", "id=" + actionId);
				}catch (e:Dynamic) {
					application.messages.addError("Can't delete group.");
				}
				if (application.db.lastAffectedRows > 0) {
					application.messages.addMessage("Group deleted.");
				}else {
					application.messages.addWarning("No group to delete.");
				}
			}
		}
		
		groups = application.db.request("SELECT * FROM _users_groups");
	}
}