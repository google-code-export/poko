/**
 * ...
 * @author Tony Polinelli
 */

package site.cms;

import php.Session;
import poko.controllers.HtmlController;
import site.cms.common.Messages;
import poko.views.View;
import site.cms.common.User;

class CmsController extends HtmlController
{
	public var messages:Messages;
	public var settings:Hash<Dynamic>;
	public var userName:String;
	
	public var authenticate:Bool;
	public var authenticationRequired:Array<String>;
	public var user:site.cms.common.User;
	
	public function new()
	{
		super();
		authenticate = true;
		authenticationRequired = [];
	}
	
	override public function init() 
	{
		super.init();
		
		messages = Messages.load("cmsMessages");
		
		user = Session.get("user") ? Session.get("user") : new User();
		user.update();
		
		if (authenticate && !user.authenticated) app.redirect("?request=cms.Index");	
	}
	
	override public function post()
	{
		messages.save();
		Session.set("user", user);
	}
}