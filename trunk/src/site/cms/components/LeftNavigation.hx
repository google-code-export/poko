/**
 * ...
 * @author ...
 */


package site.cms.components;

import poko.Component;
import php.io.File;
import php.io.FileInput;
import php.Web;

class LeftNavigation extends Component
{
	public var header:String;
	public var content:String;
	public var footer:String;
	
	public var sections:Hash < List < Dynamic >>;
	
	public function new() 
	{
		super();
		
		sections = new Hash();
	}
	
	override public function main() {}
	
	public function addSection(name:String) 
	{
		sections.set(name, new List());
	}
	
	public function addLink(section:String, title:String, link:String, ?indents:Int=0, ?external=false) 
	{
		var indentsData = [];
		indentsData[0] = "";
		indentsData[1] = "&#x02EA;&nbsp;";
		indentsData[2] = "&nbsp;&#x02EA;&nbsp;";
		indentsData[3] = "&nbsp;&nbsp;&#x02EA;&nbsp;";
		indentsData[4] = "&nbsp;&nbsp;&nbsp;&#x02EA;&nbsp;";
		var ind = indentsData[indents];
		
		sections.get(section).add( { title:title, link:link, external:external, indents:ind } );
	}
}