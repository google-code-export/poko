/**
 * ...
 * @author ...
 */

package site.cms.common;

class DefinitionElementMeta implements Dynamic
{
	public var name:String;
	public var type:String;
	public var dbtype:String;
	public var label:String;
	public var properties:Dynamic;
	public var order:Float;
	
	public var showInList:Int;
	
	public var showInFiltering:Bool;
	public var showInOrdering:Bool;
	
	public function new(name:String) 
	{
		this.properties = {}; 
		this.name = name;
	}
	
	public function toString():String
	{
		return "DefinitionElementMeta: " + name + (", Show In List: " + ((showInList == 1) ? "true" : "false"));
	}
}