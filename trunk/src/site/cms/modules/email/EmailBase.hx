/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.email;
import haxe.Serializer;
import haxe.Unserializer;
import site.cms.templates.CmsTemplate;

class EmailBase extends CmsTemplate
{
	private function setupLeftNav():Void
	{
		leftNavigation.addSection("Email");
		
		leftNavigation.addLink("Email", "Create & Send", "cms.modules.email.Index");
		
		if ( user.isAdmin() )
			leftNavigation.addLink("Email", "Settings", "cms.modules.email.Settings");
	}
	
	function loadSettings() : EmailSettings
	{
		var data = settings.get("emailSettings");
		if ( data != null && data != "" )
			return Unserializer.run(data);
		return {userTable:null, emailField:null, nameField:null, idField:null};
	}
	
	function saveSettings(s:EmailSettings) : Void
	{
		var data = Serializer.run(s);
		app.db.update("_settings", {value:data}, "`key` = 'emailSettings'");
	}
}