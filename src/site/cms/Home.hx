
package site.cms;

import poko.controllers.HtmlController;
import site.cms.templates.CmsTemplate;
import site.components.TestComponent;

class Home extends CmsTemplate
{
	public function new() 
	{
		super();
	}
	
	override public function main()
	{
		navigation.pageHeading = "Home";
	}
}