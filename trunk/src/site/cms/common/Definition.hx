/**
 * ...
 * @author ...
 */

package site.cms.common;

import poko.Application;
import haxe.Serializer;
import haxe.Unserializer;

class Definition 
{
	public var id:Int;
	public var name:String;
	public var table:String;
	public var description:String;
	public var primaryKey:String;
	public var elements:Array<DefinitionElementMeta>;
	
	public var showFiltering:Bool;
	public var showOrdering:Bool;
	
	public function new(id:Int) 
	{
		this.id = id;
		elements = new Array();
		primaryKey = "";
		
		load();
	}
	
	public function getElement(name:String):DefinitionElementMeta
	{
		for (el in elements)
			if (el.name == name)
				return el;
		return null;
	}
	
	public function addElement(name:String):DefinitionElementMeta
	{
		var exists:Bool = false;
		if (elements.length != 0)
		{
			for (el in elements)
				if (el.name == name)
					exists = true;
			if (exists) 
				return null;
		}
			
		var element = new DefinitionElementMeta(name);
		elements.push(element);
		
		return element;
	}
	
	public function removeElement(name:String)
	{
		var el:DefinitionElementMeta;
		for (el in elements)
		{
			if (el.name == name)
				elements.remove(el);
		}
	}
	
	public function reOrderElements(order:Array<String>)
	{
		if (order == null) return;
		
		for (i in 0...order.length)
			elements[i].order = Std.parseFloat(order[i]);
	
		
		elements.sort(function(el1, el2) {
			return el1.order > el2.order ? 1 : -1;
		});
		
		save();
		load();
	}
	
	public function load()
	{
		var results = Application.instance.db.requestSingle("SELECT * FROM `_definitions` WHERE `id`=\"" + id + "\"");
		
		if (results == null)
			throw("failed to load definition: " + id);
		
		name = results.name;
		table = results.table;
		description = results.description;
		primaryKey = results.primaryKey;
		
		showFiltering = results.showFiltering == 1 ? true : false;
		showOrdering = results.showOrdering == 1 ? true : false;
		
		try{
			elements = Unserializer.run(results.elements);
		} catch (e:Dynamic) {
			// first load -> save empty hash to db;
			save();
		}
	}
	
	public function save()
	{
		var data:Dynamic = { };
		
		var s:Serializer = new Serializer();
		s.serialize(elements);
		
		data.name = name;
		data.table = table;
		data.description = description;
		data.elements = s.toString();
		data.primaryKey = primaryKey;
		
		data.showFiltering = showFiltering ? 1 : 0;
		data.showOrdering = showOrdering ? 1 : 0;
		
		Application.instance.db.update("_definitions", data, "`id`=\"" + id + "\"" );
	}
	
	public static function tableToDefinitionId(table:String)
	{
		var res:Dynamic = Application.instance.db.requestSingle("SELECT `id` FROM `_definitions` WHERE `table`='" + table + "'");
		return res.id;
	}
}