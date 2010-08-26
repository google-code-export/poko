/**
 * ...
 * @author Matt Benton
 */

package site.cms.modules.email;
import site.cms.templates.CmsTemplate;

class EmailBase extends CmsTemplate
{
	private function setupLeftNav():Void
	{
		leftNavigation.addSection("Email");
		if ( user.isAdmin() )
			leftNavigation.addLink("Email", "Settings", "cms.modules.email.Settings");
	}
}