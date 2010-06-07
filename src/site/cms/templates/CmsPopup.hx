/**
 * ...
 * @author ...
 */

package site.cms.templates;

import poko.Request;
import poko.utils.Messages;
import site.cms.components.LeftNavigation;
import site.cms.components.Navigation;

class CmsPopup extends Request
{
	public var messages:List<Message>;
	public var warnings:List<Message>;
	public var errors:List<Message>;
	
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
	
	override public function preRender()
	{
		messages = application.messages.getMessages();
		warnings = application.messages.getWarnings();
		errors = application.messages.getErrors();
		
		application.messages.clearAll();		
	}
}