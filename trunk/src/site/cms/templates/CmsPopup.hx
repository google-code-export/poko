/**
 * ...
 * @author ...
 */

package site.cms.templates;

import poko.controllers.HtmlController;
import site.cms.CmsController;
import site.cms.common.Messages;
import site.cms.components.LeftNavigation;
import site.cms.components.Navigation;
import poko.controllers.Controller;

class CmsPopup extends CmsController
{
	public function new() 
	{
		super();

		authenticate = true;
	}
	
	override public function init() 
	{
		super.init();
		
		head.css.add("css/fixes/reset.css");
		head.css.add("css/fixes/fonts.css");
		
		head.css.add("css/cms/cms.css");
		head.css.add("?request=cms.services.CmsCss");
		head.css.add("css/cms/miniView.css");
		
		head.js.add("js/cms/jquery-1.3.2.min.js");
		head.js.add("js/cms/jquery.domec.js");
		head.js.add("js/main.js");
		
		//additionalJsRequests.add("site.cms.js.JsCommon");
	}
	
	override public function post()
	{
		/*messages = messages.getMessages();
		warnings = messages.getWarnings();
		errors = messages.getErrors();
		*/
		messages.clearAll();		
	}
}