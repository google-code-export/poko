 /** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko;

import poko.utils.Messages;
import haxe.Public;
import haxe.Timer;
import php.db.Mysql;
import php.Lib;
import php.Session;
import php.Web;
import poko.Db;
import poko.User;

class Application 
{
	public static var instance:Application;
	
	public var request:Request;
	
	public var isDbRequired:Bool;
	public var db:Db;
	
	public var defaultRequest:String;
	
	public var siteRoot:String;
	public var sitePath:String;
	
	public var packageRoot:String;
	public var sitePackage:String;
	
	public var uploadFolder:String;
	
	public var useGip:Bool;
	
	public var html:String;
	
	public var skipAuthentication:Bool;

	public var params:Hash<Dynamic>;
	
	public var debug:Bool;
	
	public var sessionName:String;
	
	public var messages:Messages;
	public var user:User;
	
	public var errorContactEmail:String;
	
	public function new() 
	{
		/**
		 Reduce the error levels
		 This hides a few slight bugs- like the options params bug.
		 If you have silent errors you might want to comment these out - very rare however. 
		*/
		//untyped __php__("error_reporting(E_ERROR);");
		//untyped __php__("set_error_handler('_hx_error_handler', E_ERROR);");
		
		sessionName = "poko";
		defaultRequest = "Index";
		
		instance = this;
		
		debug = false;
		
		sitePath = "";
		siteRoot = ".";
		
		packageRoot = siteRoot;
		sitePackage = "site";
		
		uploadFolder = "uploads";
		
		errorContactEmail = "contact@email.com";
		
		useGip = false;
		skipAuthentication = false;
		
		db = new Db();
		isDbRequired = true;
	}
	
	public function setupRequest():Void
	{
		setupSessionData();
		
		params = Web.getParams();
		
		var req:String = params.get("request");
		if (req == null) req = defaultRequest;
		
		var pack = sitePackage != ""?  sitePackage +".": "";
		var c:Class <Dynamic> = Type.resolveClass(pack + req);
		
		if (c == null) {
			Lib.print("<h3>404: Failed to load request</h3>Please check that the request is valid and it's class is imported.");
			php.Sys.exit(1);
		} else if (Std.is(c, Request))
		{
			Lib.print("<h3>404: Not a valid request</h3>The class is not a valid request (extends Request).");
			php.Sys.exit(1);
		}
		else 
		{
			request = Type.createInstance(c, []);
			request.application = this;
		}
	}
	
	public function execute() 
	{
		if(request == null) setupRequest();
			
		var content = request.render();
		if (content != null) 
			Lib.print(content);
		
		finalizeSessionData(); 
	}
	
	private function setupSessionData():Void
	{
		Session.setName(sessionName);
		messages = Session.get("messages") ? Session.get("messages") : new Messages();
		user = Session.get("user") ? Session.get("user") : new User();
		user.update();
	}
	
	public function finalizeSessionData() 
	{
		Session.set("messages", messages);
		Session.set("user", user);
	}
	
	public function redirect(url:String)
	{
		finalizeSessionData();
		Web.redirect(url);
		php.Sys.exit(1);
	}
}