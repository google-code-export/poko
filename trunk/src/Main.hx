package;

import poko.Application;
import poko.utils.PhpTools;
import haxe.Timer;
import php.Lib;
import php.Web;

import site.cms.ImportAll;

import site.services.Rss;
import site.services.XmlData;
import site.services.Image;

import site.Index;

class Test implements Dynamic
{
	
	public function new()
	{
		var str = "sdsds";		
		trace(str.split(""));
	}

}

class Main 
{
	static private var app:Application;
	
	public static function main() 
	{	
		PhpTools.setupTrace();
		
		//new Test();
		
		app = new Application();
		
		//app.sitePath = "http://localhost/joshmurray_texstyle/SITE/www";
		app.sitePath = "";
		app.siteRoot = ".";
		app.uploadFolder = app.siteRoot + "/res/uploads";
		app.sitePackage = "site";
		app.packageRoot = app.siteRoot;
		
		app.skipAuthentication = false;		
		app.debug = true;
		app.sessionName = "pokocms";
			
		
		if (app.debug) 
		{
			execute();
		} 
		else 
		{
			try {
				execute();	
			}
			catch (e:Dynamic){	
				Lib.print("<span style='color:#ff0000'><b>Sorry the site has died.</b></span> <br/> Please report the error to: <b>" + app.errorContactEmail + "</b>");
			}
		}
	}		
	
	public static function execute()
	{
		try {
			switch(Web.getHostName())
			{
				case "staging.touchmypixel.com":
					app.db.connect("localhost", "touchmyp_texstyle", "touchmyp_texstyl", "mextex");
				default:
					app.db.connect("192.168.1.10", "cms", "root", "");
					//app.db.connect("192.168.1.10", "joshmurray_texstyle", "root", "");
			}
			
			
		} catch (e:Dynamic)
		{
			var s = "<span style='color:#ff0000'><b>Database Connection Error: " + e + "</b></span><br/>Please edit the database settings in your applications main '.hx' file<br/>";
			
			if (app.debug) 
			{
				php.Lib.print(s);
				php.Sys.exit(1);
			}
			else throw (e);
		}
		
		app.execute();
	}
}