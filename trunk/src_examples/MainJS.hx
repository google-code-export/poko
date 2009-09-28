
package;
import js.Dom;
import js.Lib;
import poko.js.JsApplication;

import site.cms.ImportAll;


class MainJS 
{
	static private var app:JsApplication;
	
	public static function main() 
	{
        app = new JsApplication();
		app.serverUrl = "http://localhost/fwork/";
		
		js.Lib.window.onload = run;
	}
	
	public static function run(e:Event)
	{
		app.run();
	}
}
