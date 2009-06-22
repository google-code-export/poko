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

package site.cms.modules.base;

import haxe.Public;
import php.Session;
import poko.js.JsBinding;
import poko.form.elements.Input;
import poko.form.elements.Button;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.utils.PhpTools;
import haxe.Md5;
import haxe.Unserializer;
import php.io.File;
import php.Sys;
import site.cms.common.Definition;
import poko.Request;
import poko.utils.ListData;
import site.cms.common.DefinitionElementMeta;
import site.cms.modules.base.Datasets;

class Dataset extends DatasetBase
{
	public var dataset:Int;
	public var label:String;
	public var table:String;
	public var data:List<Dynamic>;
	public var fields:List<Dynamic>;
	private var fieldLabels:List<Dynamic>;
	private var definition:Definition;
	
	public var isOrderingEnabled:Bool;
	public var orderField:String;
	
	public var optionsForm:Form;
	public var showOrderBy:Bool;
	public var showFiltering:Bool;
	
	private var linkToField:String;
	private var linkTo:String;
	private var linkValueField:String;
	private var linkValue:Int;
	
	private var associateExtras:Hash<Hash<Dynamic>>;
	public var jsBind:JsBinding;
	
	public function new()
	{
		super();
	}
	
	override public function pre():Void
	{	
		super.pre();
		
		head.js.add("js/cms/jquery.qtip.min.js");
		
		dataset = Std.parseInt(application.params.get("dataset"));
		definition = new Definition(dataset);
		table = definition.table;
		label = definition.name;
		
		navigation.pageHeading += " (" + label + ")";
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDataset");
		
		remoting.addObject("api", { getFilterInfo:getFilterInfo } );
		
		// change layout for link view
		if (linkMode)
		{
			head.css.add("css/cms/miniView.css");
			this.template_file = "site/cms/templates/CmsTemplate_mini.mtt";
		}
	}
	
	
	/** remoting */
	
	public function getFilterInfo(field):Dynamic
	{
		var response:Dynamic = { };
		response.type = definition.getElement(field).type;
		response.ass= definition.getElement(field).properties.table;
		response.data = getAssocFields(field);
		return response;
	}
	
	public function getAssocFields(field:String):Hash<Dynamic>
	{
		getAssociationExtras();
		return associateExtras.get(field);
	}
	
	/** end remoting */
	
	override public function main():Void
	{		
		FilterSettings.lastDataset = table;
		
		orderField = getOrderField();
		isOrderingEnabled = orderField != null;
			
		fields = getFieldMatches();
	
		// get primary key
		var primaryData = application.db.request("SHOW COLUMNS FROM `"+table+"` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if (primaryData.length < 1)
		{
			application.messages.addError("<b>'"+table+"'</b> does not have a field set as both: <b>auto_increment</b> AND <b>primary key</b>");
			setupLeftNav();
			setContentOutput("cannot display dataset");
			return;
		} else {
			var field = primaryData.pop().Field;
			definition.primaryKey = field;
		}
		
		if (application.params.get("action")) process();
		
		getAssociationExtras();
		setupOptionsForm();
		
		var ths = this;
		fieldLabels= Lambda.map(fields, function(row:Dynamic) {	
			var el = ths.definition.getElement(row);
			return el.label != "" ? el.label : el.name; 
		});
		
		// Build SQL to get data
		var sql = "SELECT *, `" + definition.primaryKey + "` as 'cms_primaryKey' ";
		
		// get the orderfield to display
		if (orderField != null)
			sql += ", `"+orderField+"` as 'dataset__orderField' ";
		
		sql += "FROM `" + table + "` ";
		
		var hasWhere = false;
		
		var currentFilterSettings:FilterSettings = FilterSettings.get(table);
		if (application.params.get("resetState") == "true" || optionsForm.isSubmitted()) 
			currentFilterSettings.clear();
			
		//--------------------------------------------------------		
		// filtering
		
		// setup values
		var filterByValue = optionsForm.getElement('filterBy').value;
		var filterByAssocValue = optionsForm.getElement('filterByAssoc').value;
		var filterByOperatorValue = optionsForm.getElement('filterByOperator').value;
		var filterByValueValue = optionsForm.getElement('filterByValue').value;
		
		//do we load?
		if (currentFilterSettings.enabled)
		{
			filterByValue = currentFilterSettings.filterBy;
			filterByAssocValue = currentFilterSettings.filterByAssoc;
			filterByOperatorValue = currentFilterSettings.filterByOperator;
			filterByValueValue = currentFilterSettings.filterByValue;
			
			optionsForm.getElement('filterBy').value = filterByValue;
			optionsForm.getElement('filterByAssoc').value = filterByAssocValue;
			optionsForm.getElement('filterByOperator').value = filterByOperatorValue;
			optionsForm.getElement('filterByValue').value = filterByValueValue;
		}

		if((currentFilterSettings.enabled || optionsForm.isSubmitted()) && filterByValue != null && filterByValue != "")
		{
			// Associative filter
			if (definition.getElement(filterByValue).type == "association" || definition.getElement(filterByValue).type == "bool")
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
		// Only display a section of data for linking
		if (linkMode)
		{
			linkToField = application.params.get("linkToField");
			linkTo = application.params.get("linkTo");
			linkValueField= application.params.get("linkValueField");
			linkValue= Std.parseInt(application.params.get("linkValue"));
			
			if(!hasWhere)
				sql += " WHERE ";
			else 
				sql += " AND ";
				
			sql += "`" + linkToField + "`=\"" + linkTo + "\" ";
			sql += "AND `" + linkValueField + "`=\"" + linkValue + "\" ";
		}
		
		//--------------------------------------------------------
		// Ordering 
		// Use Order Field
		
		// setup values
		var orderByValue = optionsForm.getElement("orderBy").value;
		var orderByDirectionValue = optionsForm.getElement("orderByDirection").value;
		
		//do we load?
		if (currentFilterSettings.enabled)
		{
			orderByValue = currentFilterSettings.orderBy;
			orderByDirectionValue = currentFilterSettings.orderByDirection;
			
			optionsForm.getElement("orderBy").value = orderByValue;
			optionsForm.getElement("orderByDirection").value = orderByDirectionValue;
		}
		
		if (isOrderingEnabled && this.orderField != null && (!(optionsForm.isSubmitted() || currentFilterSettings.enabled) || orderByValue == null))
		{
			sql += "ORDER BY `dataset__orderField`";
		}
		// Use OrderBy Filter
		else if ((optionsForm.isSubmitted() || currentFilterSettings.enabled) && orderByValue != null && orderByValue != "")
		{
			sql += "ORDER BY `" + orderByValue + "` " + orderByDirectionValue;
		} 
		// Use Primary key
		else 
		{
			sql += "ORDER BY `" + definition.primaryKey + "`";
		}
		
		if (optionsForm.isSubmitted()){
			currentFilterSettings.enabled = true;
			
			currentFilterSettings.filterBy = filterByValue;
			currentFilterSettings.filterByAssoc = filterByAssocValue;
			currentFilterSettings.filterByOperator = filterByOperatorValue;
			currentFilterSettings.filterByValue = filterByValueValue;
			currentFilterSettings.orderBy = orderByValue;
			currentFilterSettings.orderByDirection = orderByDirectionValue;
			
			currentFilterSettings.save();
		}
		
		data = application.db.request(sql);	
		
		setupLeftNav();
	}
	
	/** Process Delete & Order */
	private function process():Void
	{
		// duplicate if that's what we're doing?!
		if (application.params.get("action") == "duplicate") {
			var id = application.params.get("id");
			if (id == null || id == "")
				return;
			
			var data = application.db.requestSingle("SELECT * FROM `"+table+"` WHERE " + definition.primaryKey + "=" + application.db.cnx.quote(Std.string(id)));

			// pre duplication field stuff
			for (element in definition.elements) {
				switch(element.type) {
					case "image":
						// duplicate the images
						var value:String = Reflect.field(data, element.name);
						var prefix = Md5.encode(Date.now().toString());
						Reflect.setField(data, element.name, prefix + value.substr(32));
						File.copy("res/uploads/" + value, "res/uploads/" + prefix + value.substr(32));
				}
			}
			
			// we have to do special stuff to make a new primary key unless it's an auto increment int field
			var tableInfo = application.db.request("SHOW FIELDS FROM `" + table + "`");
			
			var pField:Dynamic = {};
			for (f in tableInfo) {
				if (f.Field == definition.primaryKey) {
					pField = f;
					break;
				}
			}
			
			if(pField.Type.indexOf("int") == 0 && pField.Extra == "auto_increment"){
				Reflect.deleteField(data, definition.primaryKey);
			}else{
				application.messages.addError("Duplicate only works on datasets with primary keys that are auto-increment ints.");
				return;
			}
			
			application.db.insert(table, data);
			var insertedId = application.db.cnx.lastInsertId();
			
			if (application.db.lastAffectedRows > 0) {
				var element:DefinitionElementMeta;
				
				// post duplication field stuff
				for (element in definition.elements) {
					switch(element.type) {
						case "multilink":
							// add the multilinks
							var p = element.properties;
							var result = application.db.request("SELECT `" + p.linkField1 + "`, `" + p.linkField2 + "` FROM `" + p.link + "` WHERE `" + p.linkField1 + "`=" + application.db.cnx.quote(Std.string(id)));
							for (o in result) {
								Reflect.setField(o, p.linkField1, insertedId);
								application.db.insert(p.link, o);
							}
					}
				}
				
				// jump to edit mode
				var url = "?request=cms.modules.base.DatasetItem&action=edit";
				url += "&dataset=" + dataset;
				url += "&id=" + insertedId;
				url += "&linkMode=" + (linkMode ? "true" : "false");
				url += "&linkToField=" + application.params.get("linkToField");
				url += "&linkTo=" + application.params.get("linkTo");
				url += "&linkValueField=" + application.params.get("linkValueField");
				url += "&linkValue=" + application.params.get("linkValue");
				application.redirect(url);
				
			}else {
				application.messages.addError("Error duplicating item.");
			}
		}else {
			// delete
			if (php.Web.getParamValues("delete") != null)
			{
				for (delId in php.Web.getParamValues("delete"))
					application.db.delete(table, "`" + definition.primaryKey + "`='" + delId + "'");	
			}	
			
			// ordering
			if (isOrderingEnabled)
			{
				var c = 0;
				for (orderId in php.Web.getParamValues("order"))
				{
					if (orderId != null) {
						
						var d:Dynamic = { };
						Reflect.setField(d, orderField, orderId);
						application.db.update(table, d, "`"+definition.primaryKey+"`='"+c+"'");
					}
					c++;
				}
				
				c = 0;
				var res = application.db.request("SELECT `" + definition.primaryKey + "` as 'id' from " + table + " ORDER BY `" + orderField + "`");
				for (item in res)
				{
					var d:Dynamic = { };
					Reflect.setField(d, orderField, ++c);
					application.db.update(table, d, "`" + definition.primaryKey + "`='" + item.id + "'");
				}
			}			
		}
	}
	
	/** get the values of all associated fields - for naming */
	private function getAssociationExtras():Void
	{
		associateExtras = new Hash();
		var element:DefinitionElementMeta;
		for (element in definition.elements) {
			if (element.properties.type == "association" && element.properties.showAsLabel == "1") {
				var sql = "SELECT " + element.properties.field + " AS id, "+element.properties.fieldLabel + " AS label FROM " + element.properties.table;
				var result = application.db.request(sql);
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
		}
	}
	
	/** Setup the Options for to allow FilterBy and OrderBy options */
	public function setupOptionsForm()
	{
		showOrderBy = definition.showOrdering;
		showFiltering = definition.showFiltering;
		
		// No initital form values, as they are not needed (no db saving etc)
		optionsForm = new Form("options");
		optionsForm.addElement(new Selectbox("filterBy", "filterSBy"));
		optionsForm.addElement(new Selectbox("filterByOperator", "filterOperator"));
		optionsForm.addElement(new Input("filterByValue", "filterByValue"));
		optionsForm.addElement(new Selectbox("filterByAssoc", "filterByAssoc"));
		
		optionsForm.addElement(new Selectbox("orderBy", "orderBy"));
		optionsForm.addElement(new Selectbox("orderByDirection", "direction"));
		
		optionsForm.addElement(new Button("updateButton", "Update"));
		optionsForm.addElement(new Button("resetButton", "Reset", "", poko.form.elements.ButtonType.BUTTON));
		
		// populate the elements with PostBack values from the user's input
		optionsForm.populateElements();
		
		// Populate elements with data - eg all options for select boxes
		poplateOptionsFormData();
	}
	
	/** Populate form elements with thier data (depending on PostBack values) */	
	public function poplateOptionsFormData()
	{
		if (showFiltering)
		{
			var filterBySelector = optionsForm.getElementTyped("filterBy", Selectbox);
			
			for (field in fields)
				if (definition.getElement(field).showInFiltering)
				{
					var label = definition.getElement(field).label != "" ? definition.getElement(field).label : field;
					filterBySelector.addOption( { key:label, value:field } );
				}
			
			var filterAssocSelector = optionsForm.getElementTyped("filterByAssoc", Selectbox);
			var currentFilter:FilterSettings = FilterSettings.getLast();
			var filterByValue = (optionsForm.isSubmitted()) ? filterBySelector.value : currentFilter.filterBy;
			var data:Hash<Dynamic> = associateExtras.get(filterByValue);
			if (data != null)
				for (d in data.keys())
					filterAssocSelector.addOption( { key:data.get(d), value:d } );
			
			var filterOperatorSelector = optionsForm.getElementTyped("filterByOperator", Selectbox);
			filterOperatorSelector.addOption( { key:"=", value:"=" } );
			filterOperatorSelector.addOption( { key:"~", value:"~" } );
			filterOperatorSelector.addOption( { key:">", value:">" } );
			filterOperatorSelector.addOption( { key:"<", value:"<" } );
		}
		
		if (showOrderBy)
		{
			var orderBySelector = optionsForm.getElementTyped("orderBy", Selectbox);
			for (field in fields)
				if(definition.getElement(field).showInOrdering)
					orderBySelector.addOption( { key:field, value:field } );
					
			var orderByDirectionSelector = optionsForm.getElementTyped("orderByDirection", Selectbox);
			orderByDirectionSelector.addOption( { key:"ASC", value:"ASC" } );
			orderByDirectionSelector.addOption( { key:"DESC", value:"DESC" } );
		}
	}
	
	private function getOrderField():String
	{
		for (element in definition.elements)
			if (element.type == "order")
				return element.name;
		return null;
	}
	
	private function getFieldMatches():List<Dynamic>
	{
		// get a list of fields to match on
		var definitionFields:Array<String> = new Array();
		for (element in definition.elements)
			definitionFields.push(element.name);
			
		var ths = this;
		
		// get fields
		var fields = application.db.request("SHOW FIELDS FROM `" + table + "`");
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
	
	public function orderItems() 
	{
		var c = 0;
		for (val in php.Web.getParamValues("orderNum"))
		{
			if (val != null) application.db.update("news", { order:val }, "`id`=" + c);
			c++;
		}
	}
	
	public function preview(row, field)
	{
		var data:Dynamic = Reflect.field(row, field);
		
		var properties = definition.getElement(field).properties;

		return switch(properties.type)
		{
			case "text": (data).substr(0,50) + (data.length > 50 ? "..." :  "");
			case "richtext-tinymce": StringTools.htmlEscape(data.substr(0, 50)) + ((data.length > 50) ? "..." : "");
			case "richtext-wym": StringTools.htmlEscape(data.substr(0, 50)) + ((data.length > 50) ? "..." : "");
			case "image": "<img src=\"?request=cms.services.Image&preset=tiny&src="+data+"\" /> <br/>";
			case "bool": formatBool(cast data, properties);
			case "date": formatDate(cast data);
			case "keyvalue": "list of values";
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
}

class FilterSettings
{
	public var enabled:Bool;
	public var dataset:String;
	
	public var filterBy:String;
	public var filterByOperator:String;
	public var filterByAssoc:String;
	public var filterByValue:String;
	public var orderBy:String;
	public var orderByDirection:String;
	
	public static var lastDataset:String;
	
	public function new(dataset:String)
	{
		this.dataset = dataset;
		lastDataset = dataset;
		clear();
	}
	
	public function clear():Void
	{
		enabled = false;
		
		filterBy = "";
		filterByOperator = "";
		filterByAssoc = "";
		filterByValue = "";
		
		orderBy = "";
		orderByDirection = "";
		
		save();		
	}
	
	public static function get(dataset:String)
	{
		if (Session.get("datasetFilterSettings-" + dataset)) {
			return Session.get("datasetFilterSettings-" + dataset);
		}else {
			return new FilterSettings(dataset);
		}
	}
	
	public static function getLast():FilterSettings
	{
		return get(lastDataset);
	}	
	
	public function save()
	{
		Session.set("datasetFilterSettings-" + dataset, this);
	}
	
	public function toString():String
	{
		return((enabled ? "ON" : "OFF") +" dataset:" + dataset);
	}
}