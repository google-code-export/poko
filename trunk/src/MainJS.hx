
package;
import js.Dom;
import js.Lib;
import poko.js.JsApplication;

import site.cms.js.JsCommon;
import site.cms.modules.base.js.JsKeyValueInput;
import site.cms.modules.base.js.JsDefinitionElement;
import site.cms.modules.base.js.JsDatasetItem;
import site.cms.modules.base.js.JsDataset;
import site.cms.js.JsTest;

class MainJS 
{
	static private var app:JsApplication;
	
	public static function main() 
	{
        app = new JsApplication();
		app.serverUrl = "http://localhost/poko/";
		
		js.Lib.window.onload = run;
	}
	
	public static function run(e:Event)
	{
		app.run();
	}
}
