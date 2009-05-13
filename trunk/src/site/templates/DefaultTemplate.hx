/**
 * ...
 * @author ...
 */

package site.templates;

import poko.Request;

class DefaultTemplate extends Request
{	
	public function new() 
	{
		super();
	}
	
	override public function init()
	{
		head.title = "haXe poko site";
		
		//app.defaultJS.add("js/firebug-lite.js");
		head.css.add("css/reset.css");
		head.css.add("css/fonts.css");
		head.css.add("css/normal.css");
	}
}