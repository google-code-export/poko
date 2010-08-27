/**
 * ...
 * @author ...
 */

package site.cms.templates;

import poko.controllers.HtmlController;
import poko.utils.html.ScriptType;
import site.cms.CmsController;
import site.cms.common.Messages;
import site.cms.components.LeftNavigation;
import site.cms.components.Navigation;

class CmsTemplate extends CmsController
{
	public var navigation:Navigation;
	public var leftNavigation:LeftNavigation;
	
	override public function init() 
	{
		super.init();
		
		navigation = new Navigation();
		leftNavigation = new LeftNavigation();
		
		authenticate = true;
		
		settings = new Hash();
		
		head.css.add("css/fixes/reset.css");
		head.css.add("css/fixes/fonts.css");
		head.css.add("css/cms/cms.css");
		head.css.add("?request=cms.services.CmsCss");
		
		//head.addExternal(ScriptType.js, "js/cms/jquery-1.4.2.min.js");
		
		head.js.add("js/cms/jquery-1.3.2.min.js");
		head.js.add("js/cms/jquery.domec.js");
		head.js.add("js/main.js");
		
		//additionalJsRequests.add("site.cms.js.JsCommon");
		
		// get the settings
		var request:List<Dynamic> = app.db.request("SELECT * FROM _settings");
		for (i in request)
		{
			settings.set(i.key, i.value);
		}
		
		head.title = settings.get("cmsTitle");
		
		userName = user.name;
	}
	
	override public function post()
	{
		super.post();

		messages.clearAll();
	}
}