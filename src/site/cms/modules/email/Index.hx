/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.email;

import poko.form.elements.RichtextWym;
import poko.form.elements.TextArea;
import poko.form.Form;
import poko.utils.html.ScriptType;
import site.cms.templates.CmsTemplate;

class Index extends EmailBase
{	
	public var form : Form;
	
	override public function main()
	{
		super.main();
		
		head.addExternal(ScriptType.js, "js/cms/wymeditor/jquery.wymeditor.pack.js");
		
		setupLeftNav();
		
		form = new Form("form");
		
		var body = new TextArea("Email Body", "body", null);
		form.addElement(body);
		
		form.populateElements();
	}
}