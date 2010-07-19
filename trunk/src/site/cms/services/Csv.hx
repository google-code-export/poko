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

package site.cms.services;

import php.Lib;
import php.Sys;
import poko.controllers.Controller;
import php.Web;
import poko.utils.ListData;
import site.cms.common.Definition;
import site.cms.common.DefinitionElementMeta;
import site.cms.modules.base.helper.FilterSettings;

using StringTools;

class Csv extends Controller
{
	private var definition:Definition;
	private var fieldLabels:List<Dynamic>;
	public var fields:List<Dynamic>;
	public var data:List<Dynamic>;
	private var associateExtras:Hash<Hash<Dynamic>>;
	
	public function new() 
	{
		super();
	}
	
	override public function main():Void
	{
		var now = DateTools.format(Date.now(), "%a, %d %b %Y %H:%M:%S") + ' GMT';
		
		var dataset = Std.parseInt(app.params.get("dataset"));
		definition = new Definition(dataset);		
		
		Web.setHeader("Last-Modified", now);
		Web.setHeader("Expires", now);		
		Web.setHeader("Content-disposition", "attachment; filename="+definition.table+".csv");		
		Web.setHeader("content-type", "text/csv");
		
		fillData();
		
		var ths = this;
		fieldLabels = Lambda.map(fields, function(row:Dynamic) {	
			var el = ths.definition.getElement(row);
			return el.label != "" ? el.label : el.name; 
		});
		
		var str = new StringBuf();
		for (f in fieldLabels) str.add(csvefy(f)+',');
		str.add("\n");
		
		for (r in data) {
			for (f in fields) {
				str.add(csvefy(getVal(r, f))+',');
			}
			str.add("\n");
		}
		
		Lib.print(str);
	}
	
	public function getVal(row, field)
	{
		var data:Dynamic = Reflect.field(row, field);
		var properties = definition.getElement(field).properties;
		return switch(properties.type)
		{
			case "text": data;
			case "richtext-tinymce": data;
			case "richtext-wym": data;
			case "image-file": data;
			case "bool": formatBool(cast data, properties);
			case "date": formatDate(cast data);
			case "keyvalue": data;
			case "association":
				properties.showAsLabel == "1" ? associateExtras.get(field).get(cast data) : data;
			default: data;
		}
	}
	
	public function formatBool(data:Bool, properties:Dynamic)
	{
		if (properties.labelTrue == "" || properties.labelFalse == "")
			return data ? "&#x2714;" : "&#x02610;";
		else 
			return data ? properties.labelTrue : properties.labelFalse;
	}
	public function formatDate(d:Date)
	{
		if (!Std.is(d, Date))
			return null;
		
		var months = Lambda.array(ListData.getMonths());
		return d.getDate() + " " + months[d.getMonth()].key +" " + d.getFullYear();
	}	
	
	private function getOrderField():String
	{
		for (element in definition.elements)
			if (element.type == "order")
				return element.name;
		return null;
	}	

	/** get the values of all associated fields - for naming */
	private function getAssociationExtras():Void
	{
		associateExtras = new Hash();
		var element:DefinitionElementMeta;
		for (element in definition.elements) {
			if (element.properties.type == "association" && element.properties.showAsLabel == "1") {
				var sql = "SELECT " + element.properties.field + " AS id, "+element.properties.fieldLabel + " AS label FROM " + element.properties.table;
				var result = app.db.request(sql);
				var h:Hash<Dynamic> = new Hash();
				for (e in result)
					h.set(Std.string(e.id), e.label);
				associateExtras.set(element.properties.name, h);
			}
			if (element.properties.type == "bool") {
				var h:Hash<Dynamic> = new Hash();
				if (element.properties.labelTrue != "" && element.properties.labelFalse != "") {
					h.set("1", element.properties.labelTrue);
					h.set("0", element.properties.labelFalse);
				}else {
					h.set("1", "true");
					h.set("0", "false");
				}
				associateExtras.set(element.properties.name, h);
			}
			
			if (element.properties.type == "enum")
			{
				var h:Hash<Dynamic> = new Hash();
				
				var types = app.db.requestSingle("SHOW COLUMNS FROM `"+ definition.table +"` LIKE \""+element.properties.name+"\"");
				var s:String = Reflect.field(types, "Type");
				var items = s.substr(6, s.length - 8).split("','");
				
				for (item in items)
				{
					h.set(item, item);
				}
				
				associateExtras.set(element.properties.name, h);
			}
		}
	}	
	
	public function fillData()
	{
		var orderField = getOrderField();
		var isOrderingEnabled = orderField != null;
			
		fields = getFieldMatches();
	
		// get primary key
		var primaryData = app.db.request("SHOW COLUMNS FROM `"+definition.table+"` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if (primaryData.length < 1)
		{
			Lib.print('"'+definition.table+'" does not have a field set as both: "auto_increment" AND "primary key"');
			Sys.exit(1);
		} else {
			var field = primaryData.pop().Field;
			definition.primaryKey = field;
		}
	
		getAssociationExtras();
		
		// Build SQL to get data
		var sql = "SELECT *, `" + definition.primaryKey + "` as 'cms_primaryKey' ";
		
		// get the orderfield to display
		if (orderField != null)
			sql += ", `"+orderField+"` as 'dataset__orderField' ";
			
		sql += "FROM `" + definition.table + "` ";
		
		var hasWhere = false;
		
		var currentFilterSettings = FilterSettings.get(definition.table);
			
		//--------------------------------------------------------		
		// filtering
		
		// setup values
		var filterByValue = null;
		var filterByAssocValue = null;
		var filterByOperatorValue = null;
		var filterByValueValue = null;
		
		var autoFilterValue = app.params.get("autofilterBy") != "" ? app.params.get("autofilterBy") : null;
		var autoFilterByAssocValue = app.params.get("autofilterByAssoc") != "" ? app.params.get("autofilterByAssoc") : null;
	
		//do we load?
		if (currentFilterSettings.enabled)
		{
			filterByValue = currentFilterSettings.filterBy;
			filterByAssocValue = currentFilterSettings.filterByAssoc;
			filterByOperatorValue = currentFilterSettings.filterByOperator;
			filterByValueValue = currentFilterSettings.filterByValue;
		}

		if(currentFilterSettings.enabled && filterByValue != null && filterByValue != "")
		{
			// Associative filter
			if (definition.getElement(filterByValue).type == "enum" || definition.getElement(filterByValue).type == "association" || definition.getElement(filterByValue).type == "bool")
			{	
				if (filterByAssocValue != "")
					sql += "WHERE `" + filterByValue + "`='" + filterByAssocValue + "' ";
					
			
				hasWhere = true;
			}
			// Evaluate Filter
			else
			{	
				if (filterByOperatorValue != "" && filterByValueValue != "")
				{
					var op = filterByOperatorValue == "~" ? "LIKE" : filterByOperatorValue;
					var val = filterByOperatorValue == "~" ? "%" +filterByValueValue+ "%" : filterByValueValue;
					sql += "WHERE `" + filterByValue + "` " + op + " '" + val + "' ";
					
					hasWhere = true;
				}
			}
		}
		
		//--------------------------------------------------------
		// Ordering 
		// Use Order Field
		
		// setup values
		var orderByValue = null;
		var orderByDirectionValue = null;
		
		//do we load?
		if (currentFilterSettings.enabled)
		{
			orderByValue = currentFilterSettings.orderBy;
			orderByDirectionValue = currentFilterSettings.orderByDirection;
		}
				
		if (isOrderingEnabled && orderField != null && (!(currentFilterSettings.enabled) || orderByValue == null))
		{
			sql += "ORDER BY `dataset__orderField`";
		}
		// Use OrderBy Filter
		else if (currentFilterSettings.enabled && orderByValue != null && orderByValue != "")
		{
			sql += "ORDER BY `" + orderByValue + "` " + orderByDirectionValue;
		}
		// default ordering by definition
		else if (definition.autoOrderingField != "" && definition.autoOrderingField != null)
		{
			sql += "ORDER BY `" + definition.autoOrderingField + "` " + definition.autoOrderingOrder;
		}
		// Use Primary key
		else{
			sql += "ORDER BY `" + definition.primaryKey + "`";
		}
		
		data = app.db.request(sql);			
	}
	
	public static inline function csvefy(s:String):String
	{
		return '"'+s.replace('"', '""')+'"';
	}
	
	private function getFieldMatches():List<Dynamic>
	{
		// get a list of fields to match on
		var definitionFields:Array<String> = new Array();
		for (element in definition.elements)
			definitionFields.push(element.name);
			
		var ths = this;
		
		// get fields
		var fields = app.db.request("SHOW FIELDS FROM `" + definition.table + "`");
		fields = Lambda.map(fields, function(row:Dynamic) {	
			return row.Field;
		});
		
		// find matches on definition fields and table fields
		fields = Lambda.filter(fields, function(row:String) {	
			var match = Lambda.has(definitionFields, row) && ((ths.definition.getElement(row).type != "hidden" && ths.definition.getElement(row).type != "order" && ths.definition.getElement(row).showInList));
			return match || row == ths.definition.primaryKey;
		});
		
		return fields;
	}	
}