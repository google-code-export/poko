/**
 * ...
 * @author ...
 */

package site.cms.components;

import poko.Component;
import php.Web;

class Navigation extends Component
{
	public var pageHeading:String;
	public var content:String;
	private var selected:String;
	
	public function new() 
	{
		super();
		
		var name:String = application.params.get("request");
		name = name.substr(name.lastIndexOf(".") + 1);
		
		pageHeading = "page";
		
		setSelected(name);
	}
	
	override public function main() 
	{
		var requests = new Hash();
		requests.set("Home", "Home");
		requests.set("modules.base.Pages", "Pages");
		requests.set("modules.base.Datasets", "Data");
		requests.set("modules.help.Help", "Help");
		
		if (application.user.isAdmin() || application.user.isSuper()) {
			requests.set("modules.base.Users", "Users");
		}
		
		content = "<ul>";

		for (request in requests.keys())
		{
			
			if (request == selected)
			{
				content += "<li>" + requests.get(request) + "</li>";
			} else {
				content += "<li><a href=\"?request=cms."+request+"\">"+requests.get(request)+"</a></li>";
			}
		}
		
		content += "<li><a href=\"?request=cms.Index&logout=true\">logout</a></li>";
		
		content += "</ul>";
	}
	
	public function setSelected(id:String)
	{
		selected = id;
	}
	
}
