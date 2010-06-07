
package site.cms;

import poko.Request;
import site.cms.templates.CmsTemplate;

class Home extends CmsTemplate
{
	public var test:String;
	private var data:List<Dynamic>;
	
	public function new() 
	{
		super();
	}
	
	override public function main()
	{
		navigation.pageHeading = "Home";
	}
}