/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.modules.help;
import poko.TemploObject;
import site.cms.templates.CmsTemplate;

class Help extends CmsTemplate
{

	override public function main()
	{
		var topics = new Hash();
		var developerTopics = new Hash();
		
		// admin, manager
		if (application.user.isAdmin()) {
			topics.set("manager_home", "Introduction");
			topics.set("manager_about_users", "About Users");
		}else if (!application.user.isAdmin() && !application.user.isSuper()) {
			topics.set("user_home", "Introduction");
		}
		
		// normal users, but not super users (developers)
		topics.set("user_pages", "About Pages");
		topics.set("user_data", "About Data");
		
		// super user, developer
		if (application.user.isSuper())
		{
			developerTopics.set("developer_home", "Introduction");
			developerTopics.set("developer_basicConcepts", "Basic Concepts");
			developerTopics.set("developer_gettingStarted", "Getting started");
			developerTopics.set("developer_about_pages", "About Pages");
			developerTopics.set("developer_about_datasets", "About Datasets");
			developerTopics.set("developer_about_users", "About Users");
			developerTopics.set("developer_fieldDefinitions", "Field Definitions");
		}
		
		
		// Create the left Nav
		leftNavigation.addSection("General");
		leftNavigation.addSection("Developer");
		
		for (key in topics.keys())
			leftNavigation.addLink("General", topics.get(key), "cms.modules.help.Help&topic="+key);
		
		for (key in developerTopics.keys())
			leftNavigation.addLink("Developer", developerTopics.get(key), "cms.modules.help.Help&topic="+key);
		
		var topic = application.params.get("topic");
		if (topic == null) {
			if (application.user.isSuper()) {
				topic = "developer_home";
			}else if (application.user.isAdmin()) {
				topic = "manager_home";
			}else {
				topic = "user_home";
			}
		}
		setContentOutput("<div class=\"helpWrapper\">"+TemploObject.parse("site/cms/modules/help/blocks/"+topic+".mtt")+"</div>");
	}
}