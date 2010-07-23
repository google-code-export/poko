/**
 * ...
 * @author ...
 */

package site.cms;

import poko.controllers.HtmlController;
import haxe.Md5;
import php.Lib;
import php.Session;
import php.Sys;
import php.Web;
import site.cms.components.Navigation;
import site.cms.templates.CmsTemplate;


class Index extends CmsTemplate
{
	public var message:String;
	public var inputUsername:String;
	
	public function new() 
	{
		super();
		
		authenticate = false;
		message = inputUsername = "";
	}
	
	override public function main() 
	{
		//navigation = null;
		
		if (app.params.get("logout") == "true")
		{
			user.unauthenticate();
		}
		
		if (user.authenticated) 
		{ 
			app.redirect("?request=cms.modules.base.SiteView"); 
		}
		
		if (app.params.get("submitted") != null) 
		{
			var username = app.db.quote(app.params.get("username"));
			var password = app.db.quote(Md5.encode(app.params.get("password")));
			
			if (app.db.count("_users", "`username`=" + username + " AND `password`=" + password) > 0)
			{
				user.authenticate(username);
				Web.redirect(Web.getURI() + "?request=cms.modules.base.SiteView");
			} else {
				message = "Incorrect user or password";
			}	
		}
		
		if (app.params.get("username") != null) 
			inputUsername = app.params.get("username");
	}
	
}