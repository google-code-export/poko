/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tony Polinelli <tonyp@touchmypixel.com> 
 * Contributers: Tarwin Stroh-Spijer 
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   - Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   - Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE TOUCH MY PIXEL & CONTRIBUTERS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE TOUCH MY PIXEL & CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 */

package site.cms.common;

import poko.Poko;
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
	
	// create, edit, delete (don't need read) SQLs
	public var postCreateSql:String;
	public var postEditSql:String;
	public var postDeleteSql:String;
	public var postProcedure:String;
	
	public var showFiltering:Bool;
	public var showOrdering:Bool;
	
	public var helpItem:String;
	public var helpList:String;
	
	public var autoOrderingField:String;
	public var autoOrderingOrder:String;
	
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
		var results = Poko.instance.db.requestSingle("SELECT * FROM `_definitions` WHERE `id`=\"" + id + "\"");
		
		if (results == null)
			throw("failed to load definition: " + id);
		
		name = results.name;
		table = results.table;
		description = results.description;
		primaryKey = results.primaryKey;
		
		postCreateSql = results.postCreateSql;
		postEditSql = results.postEditSql;
		postDeleteSql = results.postDeleteSql;
		postProcedure = results.postProcedure;
		
		showFiltering = results.showFiltering;
		showOrdering = results.showOrdering;
		
		helpItem = results.help;
		helpList = results.help_list;
		
		autoOrderingField = "";
		autoOrderingOrder = "ASC";
		
		
		var autoOrdering:Array<String> = results.autoOrdering.split("|");
		if (autoOrdering.length == 2) {
			autoOrderingField = autoOrdering[0];
			autoOrderingOrder = autoOrdering[1];
		}

		
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
		
		data.showFiltering = showFiltering;
		data.showOrdering = showOrdering;
		
		data.postCreateSql = postCreateSql;
		data.postEditSql = postEditSql;
		data.postDeleteSql = postDeleteSql;
		data.postProcedure = postProcedure;
		
		data.help = helpItem;
		data.help_list = helpList;
		
		data.autoOrdering = autoOrderingField + "|" + autoOrderingOrder;
		
		Poko.instance.db.update("_definitions", data, "`id`=\"" + id + "\"" );
	}
	
	public static function tableToDefinitionId(table:String)
	{
		var res:Dynamic = Poko.instance.db.requestSingle("SELECT `id` FROM `_definitions` WHERE `table`='" + table + "'");
		return res.id;
	}
}