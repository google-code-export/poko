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
import poko.form.elements.Hidden;
import poko.form.Formatter;
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
import poko.controllers.HtmlController;
import poko.utils.ListData;
import site.cms.common.DefinitionElementMeta;
import site.cms.common.Procedure;
import site.cms.modules.base.Datasets;
import site.cms.modules.base.helper.FilterSettings;

using StringTools;

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
	public var allowCsv:Bool;
	
	private var linkToField:String;
	private var linkTo:String;
	private var linkValueField:String;
	private var linkValue:Int;
	
	private var associateExtras:Hash<Hash<Dynamic>>;
	public var jsBind:JsBinding;
	private var currentFilterSettings:FilterSettings;
	
	public var autoFilterValue:String;
	public var autoFilterByAssocValue:String;

	// quick holder for tabulation
	public var tabFields:List<Dynamic>;
	public var tabFilter:String;
	private var paginationLinks:String;
	
	public function new()
	{
		super();
	}
	
	override public function init():Void
	{	
		super.init();
		
		head.js.add("js/cms/jquery.qtip.min.js");
		head.js.add("js/cms/jquery.tablednd.js");
		
		dataset = Std.parseInt(app.params.get("dataset"));
		definition = new Definition(dataset);

		table = definition.table;
		label = definition.name;
		
		navigation.pageHeading += " (" + label + ")";
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDataset");
		
		remoting.addObject("api", { getFilterInfo:getFilterInfo } );
		remoting.addObject("api", { updateData:remoteUpdateData } );
		
		// change layout for link view
		if (linkMode)
		{
			head.css.add("css/cms/miniView.css");	
			layoutView.template = "cms/templates/CmsTemplate_mini.mtt";
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
	
	public function remoteUpdateData(_id:Int, _data:Dynamic, ?_hide:Bool = false):Dynamic
	{
		var response:Dynamic = { };
		
		response.hide = _hide;
		response.id = _id;
		response.error = !app.db.update(this.table, _data, '`id`='+Std.string(_id));
		
		return response;
	}
	
	/** end remoting */
	
	/*function getView()
	{
		var nl = "<br/>";
		var out = "";
		
		var linkSql = "SELECT `" + linkValueField + "` FROM " + linkTo + " WHERE `id` = " + linkValue;
		out += "linkSql: " + linkSql + nl;
		var link = Reflect.field( app.db.requestSingle( linkSql ), linkValueField );
		
		var dataSql = "SELECT * FROM paypal_transactions WHERE `" + linkToField + "` = '" + linkTo + "' AND `" + linkValueField + "` = '" + link + "'";
		out += "dataSql: " + dataSql + nl;
		var data = app.db.requestSingle( dataSql );
		
		//var data = app.db.requestSingle( "SELECT * FROM "+linkTo+" WHERE `txn_id` = 'matt'" );
		
		
		
		
		out += "data: " + data + nl;
		out += "dataset: " + dataset + nl;
		out += "definition: " + definition + nl;
		out += "table: " + table + nl;
		out += "linkToField: " + linkToField + nl;
		out += "linkTo: " + linkTo + nl;
		out += "linkValueField: " + linkValueField + nl;
		out += "linkValue: " + linkValue + nl;
		return out;
	}*/
	
	override public function main():Void
	{		
		FilterSettings.lastDataset = table;
		
		orderField = getOrderField();
		isOrderingEnabled = orderField != null;
			
		fields = getFieldMatches();
	
		// get primary key
		var primaryData = app.db.request("SHOW COLUMNS FROM `" + table + "` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
		if (primaryData.length < 1)
		{
			messages.addError("<b>'"+table+"'</b> does not have a field set as both: <b>auto_increment</b> AND <b>primary key</b>");
			setupLeftNav();
			setContentOutput("cannot display dataset");
			return;
		} else {
			var field = primaryData.pop().Field;
			definition.primaryKey = field;
		}
		
		if (app.params.get("action")) process();
		
		getAssociationExtras();
		setupOptionsForm();
		
		// do we show CSV download?
		allowCsv = definition.allowCsv;
		
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
		
		currentFilterSettings = FilterSettings.get(table);
		if (app.params.get("resetState") == "true" || optionsForm.isSubmitted())
			currentFilterSettings.clear();

		//--------------------------------------------------------		
		// tabulation
		if (definition.params.useTabulation) {
			tabFields = Unserializer.run(definition.params.tabulationFields);
			tabFilter = app.params.get("tabFilter");
			if (tabFilter == null) tabFilter = "";
		}
			
		//--------------------------------------------------------		
		// filtering
		
		// setup a sqlP for params so we can use later
		var sqlWhere = "";
		
		// setup values
		var filterByValue = optionsForm.getElement('filterBy').value;
		var filterByAssocValue = optionsForm.getElement('filterByAssoc').value;
		var filterByOperatorValue = optionsForm.getElement('filterByOperator').value;
		var filterByValueValue = optionsForm.getElement('filterByValue').value;
		
		autoFilterValue = app.params.get("autofilterBy") != "" ? app.params.get("autofilterBy") : null;
		autoFilterByAssocValue = app.params.get("autofilterByAssoc") != "" ? app.params.get("autofilterByAssoc") : null;
		if (autoFilterValue != null && autoFilterByAssocValue != null && optionsForm.isSubmitted()) {
			currentFilterSettings.enabled = false;
			sqlWhere += "WHERE `" + autoFilterValue + "`='" + autoFilterByAssocValue + "' ";
			 
			hasWhere = true;
		}
		
		
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
			var elType = definition.getElement(filterByValue).type;
			
			// Associative filter
			if (elType == "enum" || elType == "association" || elType == "bool")
			{
				if (filterByAssocValue != "")
					sqlWhere += "WHERE `" + filterByValue + "`='" + filterByAssocValue + "' ";
					
			
				hasWhere = true;
			}
			// Evaluate Filter
			else
			{	
				if (filterByOperatorValue != "" && filterByValueValue != "")
				{
					var op = filterByOperatorValue == "~" ? "LIKE" : filterByOperatorValue;
					var val = filterByOperatorValue == "~" ? "%" +filterByValueValue+ "%" : filterByValueValue;
					
					if(elType == "date"){
						sqlWhere += "WHERE `" + filterByValue + "` " + op + " " + val + " ";
					}else {
						sqlWhere += "WHERE `" + filterByValue + "` " + op + " '" + val + "' ";
					}
					
					hasWhere = true;
				}
			}
		}
		
		if (tabFilter != null && tabFilter != "") {
			if (!hasWhere) sqlWhere += " WHERE ";
			sqlWhere += " " + tabFilter + " ";
		}
		
		//--------------------------------------------------------
		// Only display a section of data for linking
		if (linkMode)
		{
			linkToField = app.params.get("linkToField");
			linkTo = app.params.get("linkTo");
			linkValueField= app.params.get("linkValueField");
			linkValue= Std.parseInt(app.params.get("linkValue"));
			
			if(!hasWhere)
				sqlWhere += " WHERE 1=1 ";
				

			if(linkToField != null && linkToField != "")
				sqlWhere += "AND `" + linkToField + "`=\"" + linkTo + "\" ";
			
			sqlWhere += "AND `" + linkValueField + "`=\"" + linkValue + "\" ";
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
		
		var sqlOrder = "";
		
		// default ordering by definition
		if (definition.autoOrderingField != "" && definition.autoOrderingField != null)
		{
			sqlOrder += "ORDER BY `" + definition.autoOrderingField + "` " + definition.autoOrderingOrder;
		}
		// order by order field
		else if (isOrderingEnabled && this.orderField != null && (!(optionsForm.isSubmitted() || currentFilterSettings.enabled) || orderByValue == null))
		{
			sqlOrder += "ORDER BY `dataset__orderField`";
		}
		// Use OrderBy Filter
		else if ((optionsForm.isSubmitted() || currentFilterSettings.enabled) && orderByValue != null && orderByValue != "")
		{
			sqlOrder += "ORDER BY `" + orderByValue + "` " + orderByDirectionValue;
		}
		// Use Primary key
		else{
			sqlOrder += "ORDER BY `" + definition.primaryKey + "`";
		}
		
		var sqlLimit = " ";
		paginationLinks = "";
		
		if (definition.params.usePaging) {
			var maxRecords = app.db.requestSingle("SELECT COUNT(*) as count FROM `" + table + "` " + sqlWhere).count;
			var perPage = definition.params.perPage;
			var maxPage = Math.ceil(maxRecords / perPage) - 1;
			var wantedPage = app.params.get("page");
			if (wantedPage == null) wantedPage = 0;
			if (wantedPage > maxPage) wantedPage = maxPage;
			var startAt = perPage * wantedPage;
			if (startAt < 0) startAt = 0;
			sqlLimit += " LIMIT " + startAt + ", " + perPage;
			paginationLinks = pagination(maxPage + 1, wantedPage, definition.params.pagingRange);
		}
		
		if (optionsForm.isSubmitted() && optionsForm.getElement("reset").value != "true"){
			currentFilterSettings.enabled = true;
			
			currentFilterSettings.filterBy = filterByValue;
			currentFilterSettings.filterByAssoc = filterByAssocValue;
			currentFilterSettings.filterByOperator = filterByOperatorValue;
			currentFilterSettings.filterByValue = filterByValueValue;
			currentFilterSettings.orderBy = orderByValue;
			currentFilterSettings.orderByDirection = orderByDirectionValue;
			
			currentFilterSettings.save();
		}
		
		sql = sql + " " + sqlWhere + " " + sqlOrder + " " + sqlLimit;
		data = app.db.request(sql);	
		
		setupLeftNav();
	}
	
	public function pagination(numberOfPages:Int, currentPage:Int = 0, ?range:Int = 4)
	{
		var showItems = (range * 2) + 1; 
		currentPage++;
		
		var o = "";
		
		if (currentPage > 2 && currentPage > range + 1 && showItems < numberOfPages) o += "<a href='" + getPageLink(1) + "'>&laquo;</a>";
		if (currentPage > 1 && showItems < numberOfPages) o += "<a href='" + getPageLink(currentPage - 1) + "'>&lsaquo;</a>";
		
		for (i in 1...numberOfPages + 1)
		{
			if (1 != numberOfPages && ( !(i >= currentPage + range + 1 || i <= currentPage-range-1) || numberOfPages <= showItems ))
			{
				o += (currentPage == i)? "<span class=\"paginationCurrent\">" + i + "</span>":"<a href='" + getPageLink(i) + "' class=\"paginationInactive\">" + i + "</a>";
			}
		}

		if (currentPage < numberOfPages && showItems < numberOfPages) o += "<a href=\"" + getPageLink(currentPage + 1) + "\">&rsaquo;</a>";
		if (currentPage < numberOfPages - 1 && currentPage + range-1 < numberOfPages && showItems < numberOfPages) o += "<a href='" + getPageLink(numberOfPages) + "'>&raquo;</a>";
		o += "<span class=\"paginationOf\">Page " + currentPage + " of " + numberOfPages + "</span>";
		
		return o;
	}
	
	public function getPageLink(page:Int)
	{
		page = page - 1;
		return "?request=cms.modules.base.Dataset&dataset=" + dataset
			+ "&tabFilter=" + StringTools.urlEncode(tabFilter)
			+ "&page=" + Std.string(page)
			+ "&siteMode=" + (siteMode ? "true" : "false")
			+ "&linkMode=" + linkMode
			+ "&linkToField=" + linkToField
			+ "&linkTo=" + linkTo
			+ "&linkValueField=" + linkValueField
			+ "&linkValue=" + linkValue
			+ "&autofilterBy" + autoFilterValue
			+ "&autofilterByAssoc" + autoFilterByAssocValue;
	}
	
	/** Process Delete & Order */
	private function process():Void
	{
		// duplicate if that's what we're doing?!
		if (app.params.get("action") == "duplicate") {
			var id = app.params.get("id");
			if (id == null || id == "")
				return;
			
			var data = app.db.requestSingle("SELECT * FROM `"+table+"` WHERE " + definition.primaryKey + "=" + app.db.quote(Std.string(id)));

			// pre duplication field stuff
			for (element in definition.elements) {
				switch(element.type) {
					case "image-file":
						// duplicate the images
						var value:String = Reflect.field(data, element.name);
						var prefix = Md5.encode(Date.now().toString());
						Reflect.setField(data, element.name, prefix + value.substr(32));
						File.copy("res/uploads/" + value, "res/uploads/" + prefix + value.substr(32));
				}
			}
			
			// we have to do special stuff to make a new primary key unless it's an auto increment int field
			var tableInfo = app.db.request("SHOW FIELDS FROM `" + table + "`");
			
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
				messages.addError("Duplicate only works on datasets with primary keys that are auto-increment ints.");
				return;
			}
			
			app.db.insert(table, data);
			var insertedId = app.db.lastInsertId;
			
			if (app.db.lastInsertId > 0) {
				var element:DefinitionElementMeta;
				
				// post duplication field stuff
				for (element in definition.elements) {
					switch(element.type) {
						case "multilink":
							// add the multilinks
							var p = element.properties;
							var result = app.db.request("SELECT `" + p.linkField1 + "`, `" + p.linkField2 + "` FROM `" + p.link + "` WHERE `" + p.linkField1 + "`=" + app.db.quote(Std.string(id)));
							for (o in result) {
								Reflect.setField(o, p.linkField1, insertedId);
								app.db.insert(p.link, o);
							}
					}
				}
				
				// jump to edit mode
				var url = "?request=cms.modules.base.DatasetItem&action=edit";
				url += "&dataset=" + dataset;
				url += "&id=" + insertedId;
				url += "&linkMode=" + (linkMode ? "true" : "false");
				url += "&linkToField=" + app.params.get("linkToField");
				url += "&linkTo=" + app.params.get("linkTo");
				url += "&linkValueField=" + app.params.get("linkValueField");
				url += "&linkValue=" + app.params.get("linkValue");
				url += "&autofilterByAssoc=" + app.params.get("autofilterByAssoc");
				url += "&autofilterBy=" + app.params.get("autofilterBy");
				if (siteMode) url += "&siteMode=true";
				messages.addMessage("Record duplicated.");
				app.redirect(url);
				
			}else {
				messages.addError("Error duplicating item.");
			}
		}else {
			if(app.params.get("submitted_delete") != null){
				// delete
				if (php.Web.getParamValues("delete") != null)
				{
					// see if we need to do pre-SQL?
					var runSqlPerRow:Bool = false;
					if (definition.postDeleteSql.indexOf("#") != -1){
						runSqlPerRow = true;
						messages.addDebug("Post-delete SQL running per row");
					}else if (definition.postDeleteSql != null && definition.postDeleteSql != ""){
						app.db.request(definition.postDeleteSql);
					}

					var numDeleted = 0;
					for (delId in php.Web.getParamValues("delete"))
					{
						// post processing setup
						var postProcedure:Procedure = null;
						if (definition.postProcedure != null && definition.postProcedure != "") {
							var c:Class<Dynamic> = Type.resolveClass(definition.postProcedure);
							if (c != null) {
								postProcedure = Type.createInstance(c, []);
								if (!Std.is(postProcedure, Procedure)) {
									postProcedure = null;
								}
							}
						}						
						
						// pre-fetch the deleted data
						var tData = new List();
						if(runSqlPerRow || postProcedure != null){
							tData = app.db.requestSingle("SELECT * FROM `" + table + "` WHERE `" + definition.primaryKey + "`='" + delId + "'");
						}
						
						// delete our row
						try{
							app.db.delete(table, "`" + definition.primaryKey + "`='" + delId + "'");
							numDeleted++;
						}catch (e:Dynamic) {
							messages.addError("There was a problem deleting your data.");
							if (runSqlPerRow) messages.addWarning("Post-delete SQL not run as delete run completed.");
							runSqlPerRow = false;
						}
						
						// now run the post delete if there was no problem running our delete
						if(runSqlPerRow){
							var tSql = definition.postDeleteSql;
							for (tField in Reflect.fields(tData)) {
								tSql = StringTools.replace(tSql, "#" + tField + "#", Reflect.field(tData, tField));
							}
							try {
								app.db.request(tSql);
							}catch (e:Dynamic) {
								messages.addError("Post-delete SQL had problems: " + tSql);
							}
						}
						
						// do post procedure
						if(postProcedure != null) postProcedure.postDelete(table, tData);
					}
					messages.addMessage(numDeleted+" record(s) deleted.");
				}else {
					messages.addWarning("Select records to delete.");
				}
			}else if(app.params.get("submitted_order") != null){
			
				// ordering
				if (isOrderingEnabled)
				{
					var c = 0;
					for (orderId in php.Web.getParamValues("order"))
					{
						if (orderId != null) {
							
							var d:Dynamic = { };
							Reflect.setField(d, orderField, orderId);
							app.db.update(table, d, "`"+definition.primaryKey+"`='"+c+"'");
						}
						c++;
					}
					
					c = 0;
					var res = app.db.request("SELECT `" + definition.primaryKey + "` as 'id' from " + table + " ORDER BY `" + orderField + "`");
					var numSorted = 0;
					for (item in res)
					{
						var d:Dynamic = { };
						Reflect.setField(d, orderField, ++c);
						app.db.update(table, d, "`" + definition.primaryKey + "`='" + item.id + "'");
						numSorted++;
					}
					
					messages.addMessage(numSorted+" record(s) sorted.");
				}else {
					messages.addError("Tried to order records that are not allowed to be re-ordered!");
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
				
				var types = app.db.requestSingle("SHOW COLUMNS FROM `"+ table +"` LIKE \""+element.properties.name+"\"");
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
		
		optionsForm.addElement(new Hidden("reset", "false"));
		
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
					filterBySelector.addOption( { key:field, value:label } );
				}
			
			var filterAssocSelector = optionsForm.getElementTyped("filterByAssoc", Selectbox);
			var currentFilter:FilterSettings = FilterSettings.getLast();
			var filterByValue = (optionsForm.isSubmitted()) ? filterBySelector.value : currentFilter.filterBy;
			var data:Hash<Dynamic> = associateExtras.get(filterByValue);
			if (data != null)
				for (d in data.keys())
					filterAssocSelector.addOption( { key:d, value:data.get(d) } );
			
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
		var fields = app.db.request("SHOW FIELDS FROM `" + table + "`");
		fields = Lambda.map(fields, function(row:Dynamic) {	
			return row.Field;
		});
		
		// add listformatter items
		for (element in definition.elements)
			if (element.type == "listformatter")
				if (element.showInList)
					fields.add(element.name);
		
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
			if (val != null) app.db.update("news", { order:val }, "`id`=" + c);
			c++;
		}
	}
	
	public function preview(row, field)
	{
		var data:Dynamic = Reflect.field(row, field);
		var properties = definition.getElement(field).properties;
		if (properties.formatter != null && properties.formatter != "" && properties.type != "listformatter")
		{
			var f:Formatter = Type.createInstance(Type.resolveClass(properties.formatter), []);
			return f.format(data);
		} else {
			return switch(properties.type)
			{
				case "text": StringTools.htmlEscape((data).substr(0,50)) + (data.length > 50 ? "..." :  "");
				case "richtext-tinymce": StringTools.htmlEscape(data.substr(0, 50)) + ((data.length > 50) ? "..." : "");
				case "richtext-wym": StringTools.htmlEscape(data.substr(0, 50)) + ((data.length > 50) ? "..." : "");
				case "image-file":
					if (properties.isImage == "1"){
						"<a target=\"_blank\" href=\"?request=cms.services.Image&src=" + data + "\"><img src=\"?request=cms.services.Image&preset=tiny&src=" + data + "\" /></a> <br/>";
					}else {
						if(data){
							"<a target=\"_blank\" href=\"./res/uploads/" + data + "\" />file</a>";
						}else {
							"empty";
						}
					}
				case "bool": formatBool(cast data, properties);
				//case "date": formatDate(cast data);
				case "date": formatDate( cast data, properties.mode );
				case "keyvalue": "list of values";
				case "association":
					properties.showAsLabel == "1" ? associateExtras.get(field).get(cast data) : data;
				case "listformatter":
					var f:Formatter = Type.createInstance(Type.resolveClass(properties.formatter), []);
					return f.format(row);
				default: data;
			}
		}
	}
	
	public function formatBool(data:Bool, properties:Dynamic)
	{
		if (properties.labelTrue == "" || properties.labelFalse == "")
			return data ? "&#x2714;" : "&#x02610;";
		else 
			return data ? properties.labelTrue : properties.labelFalse;
	}
	
	public function formatDate( d : Date, ?mode : String = "DATETIME" )
	{
		if (!Std.is(d, Date))
			return null;
		
		var out = "";
		
		var months = Lambda.array(ListData.getMonths());
		
		if ( mode == "DATE" || mode == "DATETIME" )
			out = d.getDate() + " " + months[d.getMonth()].key +" " + d.getFullYear();
		
		if ( mode == "TIME" || mode == "DATETIME" )
			out += " " + Std.string(d.getHours()).lpad("0", 2) + ":" + Std.string(d.getMinutes()).lpad("0", 2) + ":" + Std.string(d.getSeconds()).lpad("0", 2);
			
		return out;
	}
}