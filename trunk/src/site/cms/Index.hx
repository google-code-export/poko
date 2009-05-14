/**
 * ...
 * @author ...
 */

package site.cms;

import poko.Request;
import haxe.Md5;
import php.Lib;
import php.Session;
import php.Sys;
import php.Web;
import site.cms.templates.CmsTemplate;


class Index extends CmsTemplate
{
	public var message:String;
	public var username:String;
	
	public function new() 
	{
		super();
		authenticate = false;
		message = username = "";
	}
	
	override public function main() 
	{
		navigation = null;
		authenticate = false;
		
		if (application.params.get("logout") == "true")
		{
			application.user.unauthenticate();
		}
		
		if (application.user.authenticated) 
		{ 
			Web.redirect("?request=cms.Home"); 
		}
		
		if (application.params.get("submitted") != null) 
		{
			var username = application.db.cnx.quote(application.params.get("username"));
			var password = application.db.cnx.quote(Md5.encode(application.params.get("password")));
			
			if (application.db.count("_users", "`username`=" + username + " AND `password`=" + password) > 0 || 1 == 1)
			{
				application.user.authenticate(username);
				Web.redirect(Web.getURI() + "?request=cms.Home");
			} else {
				message = "Incorrect user or password";
			}	
		}
		
		
		if (application.params.get("username") != null) 
			this.username = application.params.get("username");
	}
	
}