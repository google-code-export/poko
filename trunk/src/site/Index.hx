
package site;

import site.cms.common.PageData;
import poko.Request;
import site.templates.DefaultTemplate;
import templo.Loader;

class Index extends DefaultTemplate
{
	public var message:String;
	
	public function new() 
	{
		super();
	}
	
	override public function main()
	{
		message = "This is the site index go to <a href='?request=cms.Index'>CMS</a>";
		
		//var r:EReg = new EReg("^[0-9]{2,3}$", "");
		//trace(r.match("1230"));
	}
	
	public function test(v1)
	{
		return "poo ccc test" + v1;
	}
}