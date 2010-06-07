/**
 * ...
 * @author ...
 */

package site.cms.templates;

import poko.Request;
import poko.utils.Messages;
import site.cms.common.CmsSettings;
import site.cms.components.LeftNavigation;
import site.cms.components.Navigation;

class CmsTemplate extends Request
{
	public var navigation:Navigation;
	public var leftNavigation:LeftNavigation;
	public var messages:List<Message>;
	public var warnings:List<Message>;
	public var errors:List<Message>;
	public var debugs:List<Message>;
	public var userName:String;
	
	public function new() 
	{
		super();

		navigation = new Navigation();
		leftNavigation = new LeftNavigation();
		
		authenticate = true;
	}
	
	override public function init() 
	{
		super.init();
		
		head.css.add("css/fixes/reset.css");
		head.css.add("css/fixes/fonts.css");
		head.css.add("css/cms/cms.css");
		head.css.add("?request=cms.services.CmsCss");
		
		head.js.add("js/cms/jquery-1.3.2.min.js");
		head.js.add("js/cms/jquery.domec.js");
		head.js.add("js/main.js");
		
		//additionalJsRequests.add("site.cms.js.JsCommon");
		
		// get the settings
		CmsSettings.load(application.db);
		
		head.title = CmsSettings.i.cmsTitle;
		
		userName = application.user.name;
	}
	
	override public function preRender()
	{
		messages = application.messages.getMessages();
		warnings = application.messages.getWarnings();
		errors = application.messages.getErrors();
		debugs = application.messages.getDebugs();
				
		application.messages.clearAll();
	}
	
	override public function post()
	{
		CmsSettings.save(application.db);
	}
}