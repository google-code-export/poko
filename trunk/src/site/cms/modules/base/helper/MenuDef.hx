/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base.helper;

class MenuDef 
{
	public var headings:Array<MenuHeading>;
	public var items:Array<MenuItem>;
	public var numberOfSeperators:Int;
	
	public function new(?headings:Array<MenuHeading>, ?items:Array<MenuItem>) 
	{
		this.headings = headings != null ? headings : new Array();
		this.items = items != null ? items : new Array();
		numberOfSeperators = 0;
	}
	
	public function addHeading(name:String)
	{
		headings.push( { name:name, isSeperator: false } );
	}
	
	public function addSeperator()
	{
		numberOfSeperators++;
		headings.push( { name:"__sep"+numberOfSeperators+"__", isSeperator: true } );
	}
	
	public function addItem(id:Int, type:MenuItemType, name:String, ?heading:String = null, ?indent:Int = 0)
	{
		items.push( {
			id: id,
			type: type,
			name: name,
			heading: heading,
			indent: indent
		});
	}
}

typedef MenuHeading = {
	var name:String;
	var isSeperator:Bool;
}

typedef MenuItem = {
	var id:Int;
	var type:MenuItemType;
	var name:String;
	var indent:Int;
	var heading:String;
}

enum MenuItemType {
	PAGE;
	DATASET;
}