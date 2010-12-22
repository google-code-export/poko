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

import php.FileSystem;
import php.io.File;
import poko.form.elements.KeyVal;
import poko.form.elements.LocationSelector;
import poko.form.elements.RichtextWym;
import poko.form.validators.DateTimeValidator;
import poko.js.JsBinding;
import haxe.Md5;
import poko.controllers.HtmlController;
import site.cms.common.DateTimeMode;
import site.cms.common.DefinitionElementMeta;
import poko.form.elements.Button;
import poko.form.elements.CheckboxGroup;
import site.cms.common.Procedure;
import site.cms.modules.base.formElements.KeyValueInput;
import poko.form.elements.DateSelector;
import site.cms.modules.base.formElements.FileUpload;
import poko.form.elements.Hidden;
import poko.form.elements.Input;
import poko.form.elements.RadioGroup;
import poko.form.elements.Readonly;
import poko.form.elements.Richtext;
import poko.form.elements.Selectbox;
import poko.form.elements.TextArea;
import poko.form.Form;
import poko.form.FormElement;
import poko.form.validators.NumberValidator;
import poko.form.validators.RegexValidator;
import poko.form.validators.StringValidator;
import poko.utils.PhpTools;
import php.Lib;
import php.Sys;
import php.Web;
import haxe.Unserializer;
import haxe.Serializer;
import site.cms.modules.base.formElements.LinkTable;
import site.cms.PokoCms;

import site.cms.modules.base.Datasets;
import site.cms.common.Definition;

class DatasetItem extends DatasetBase
{
	public var id:Int;
	public var dataset:Int;
	public var table:Dynamic;
	public var definition:Definition;
	public var label:String;
	public var form:Form;
	
	public var page:String;
	public var data:Dynamic;
	
	public var isOrderingEnabled:Bool;
	public var orderField:String;
	
	public var jsBind:JsBinding;
	
	public var autoFilterValue:String;
	public var autoFilterByAssocValue:String;
	
	public var singleInstanceEdit:Bool;
	
	public function new() 
	{
		super();
	}
	
	override public function init()
	{
		super.init();
		
		head.js.add("js/cms/jquery-ui-1.7.2.custom.min.js");
		head.css.add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");
		head.js.add("js/cms/jquery.qtip.min.js");
		
		head.js.add("js/cms/tiny_mce/tiny_mce.js");
		head.js.add("js/cms/tiny_mce_browse.js");
		head.js.add("js/cms/wymeditor/jquery.wymeditor.pack.js");
		head.js.add("js/cms/wym_editor_browse.js");
		//head.js.add("js/cms/jquery.windows-engine.js");
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDatasetItem");
		remoting.addObject("api", { deleteFile:deleteFile } );
		
		singleInstanceEdit = app.params.get('singleInstanceEdit');
		
		// change layout for link view
		if (linkMode)
		{
			head.css.add("css/cms/miniView.css");
			layoutView.template = "cms/templates/CmsTemplate_mini.mtt";
		}
	}
	
	override public function main()
	{
		data = { };
		
		id = Std.parseInt(app.params.get('id'));
		dataset = Std.parseInt(app.params.get("dataset"));
		isOrderingEnabled = false;
				
		if (!pagesMode)
		{
			// Dataset Mode
			
			definition = new Definition(dataset);
			label = definition.name;
			table = definition.table;
			data = app.db.requestSingle("SELECT * FROM `" + table + "` WHERE `id`=" + app.db.quote(Std.string(id)));
			
			orderField = getOrderField();
			isOrderingEnabled = orderField != null;
			
			autoFilterValue = app.params.get("autofilterBy") != "" ? app.params.get("autofilterBy") : null;
			autoFilterByAssocValue = app.params.get("autofilterByAssoc") != "" ? app.params.get("autofilterByAssoc") : null;
			
		} else {
			
			// Pages mode
			
			var result = app.db.requestSingle("SELECT * FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id AND p.id=" + app.db.quote(Std.string(id)));
			label = page = result.name;
			
			data = (result.data != "" && result.data != null) ? cast Unserializer.run(result.data) : {};
				
			definition = new Definition(result.definitionId);
		}
		
		navigation.pageHeading += " (" + label + ")";
		
		setupForm();
		
		if (form.isSubmitted() && form.isValid()) processForm();
		
		setupForm();
		
		setupLeftNav();
	}
	
	public function deleteFile(filename:String, display:String)
	{
		id = Std.parseInt(app.params.get('id'));
		dataset = Std.parseInt(app.params.get("dataset"));
		definition = new Definition(dataset);
		table = definition.table;
		
		try {
			var d:Hash<String> = new Hash();
			d.set(display.substr(18), "");
			var result = app.db.update(table, d, "id="+id);
			return {
				success: FileSystem.deleteFile(PokoCms.uploadFolder + "/" + filename) && result,
				display: display,
				error: null
			};
		}catch (e:Dynamic) {
			return { success: false, display: display, error: e };
		}
	}
	
	private function processForm():Void
	{
		var data = form.getData();
		
		// upload files
		var uploadData = uploadFiles();
		for (k in uploadData.keys()) {
			Reflect.setField(data, k, uploadData.get(k));
		}
		
		// post processing
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
		
		switch(form.getElement("__action").value)
		{	
			case "add":
				if (isOrderingEnabled)
				{
					var result = app.db.requestSingle("SELECT MAX(`" + orderField + "`) as 'order' FROM `"+table+"`");
					Reflect.setField(data, orderField, Std.string(result.order + 1));
				}
				
				// do update
				var doPost = true;
				try{
					app.db.insert(table, data);
					id = app.db.lastInsertId;
				}catch (e:Dynamic) {
					doPost = false;
					messages.addError("Update failed. Not running post commands or procedures.");
				}
				
				if (doPost) {
					// add the ID to SQL, have to get primary key first
					//var primaryData = app.db.request("SHOW COLUMNS FROM `"+table+"` WHERE `Key`='PRI' AND `Extra`='auto_increment'");
					//if (primaryData.length > 0)
					var primaryKey = app.db.getPrimaryKey(table);
					if (primaryKey != null)
					{
						//var primaryKey = primaryData.pop().Field;
						Reflect.setField(data, primaryKey, id);
						
						// run post SQL
						if(definition.postCreateSql != null && definition.postCreateSql != ""){
							var tSql = definition.postCreateSql;
							for (tField in Reflect.fields(data)) {
								tSql = StringTools.replace(tSql, "#" + tField + "#", Reflect.field(data, tField));
							}
							try {
								app.db.query(tSql);
							}catch (e:Dynamic) {
								messages.addError("Post-create SQL had problems: " + tSql);
							}
						}						
					} else {
						messages.addError("Could not get the primary key from newly created record. Post-SQL not run.");
					}
					
					// run post procedure
					if(postProcedure != null) postProcedure.postCreate(table, data, id);
				}
			case "edit": 
				if (pagesMode)
				{
					var sdata = Serializer.run(data);
					app.db.update("_pages", {data:sdata}, "`id`=" + app.db.quote(Std.string(id)));
				} else {
					// get old data before update
					var oldData:Dynamic = app.db.requestSingle("SELECT * FROM `"+table+"` WHERE `id`=" + app.db.quote(Std.string(id)));
					
					// do update
					var doPost = true;
					try{
						app.db.update(table, data, "`id`=" + app.db.quote(Std.string(id)));
					}catch (e:Dynamic) {
						doPost = false;
						messages.addError("Update failed. Not running post commands or procedures.");
					}
					
					if(doPost){
						// run post SQL
						if(definition.postEditSql != null && definition.postEditSql != ""){
							var tSql = definition.postEditSql;
							for (tField in Reflect.fields(oldData)) {
								tSql = StringTools.replace(tSql, "#" + tField + "#", Reflect.field(oldData, tField));
							}
							for (tField in Reflect.fields(data)) {
								tSql = StringTools.replace(tSql, "*" + tField + "*", Reflect.field(data, tField));
							}
							try {
								app.db.query(tSql);
							}catch (e:Dynamic) {
								messages.addError("Post-delete SQL had problems: " + tSql);
							}
						}
					
						// run post procedure
						if (postProcedure != null) postProcedure.postUpdate(table, oldData, data);
					}
				}
		}

		// update multilink linkTos
		var elements = pagesMode ? Lambda.list(definition.elements) : getElementMatches();
		for (element in elements)
		{
			if (element.type == "multilink")
			{
				// delete existing links
				app.db.delete(element.properties.link, "`" + element.properties.linkField1 + "`=" + app.db.quote(Std.string(id)));
				
				// insert new links
				for (check in cast(Reflect.field(data, element.name), Array<Dynamic>))
				{
					var d:Dynamic = { };
					Reflect.setField(d, element.properties.linkField1, id);
					Reflect.setField(d, element.properties.linkField2, check);
					
					app.db.insert(element.properties.link, d); 
				}
			}
			
			if (element.type == "post-sql-value")
			{
				// get the value of the key we want from current table
				var updateKeyValue = Reflect.field(data, element.properties.updateKey);
				
				// get primary key
				var primaryKey = app.db.getPrimaryKey(table);
				if (primaryKey != null && updateKeyValue != null)
				{
					// get the value
					var result = app.db.requestSingle("SELECT `"+element.properties.updateTo+"` AS `__v` FROM `"+element.properties.table+"` WHERE `" + primaryKey + "`='"+updateKeyValue+"'");
					
					// update usign the values ...
					var tData = { };
					try {
						Reflect.setField(tData, element.name, result.__v);
						app.db.update(table, tData, "`id`=" + app.db.quote(Std.string(id)));	
					} catch (e:Dynamic)
					{
						messages.addError("There is an error in your 'post-sql-value' setup for field: " + element.name);
					}
									
				} else {
					messages.addWarning("There was a problem updating your post SQL field because there was no primary key for the target table.");
				}
			}
			
			if (element.type == "date")
			{
				// was doing this but really only needed a "read only" from the front end dir!
				/*if(element.properties.currentOnAdd == "1"){
					var tData = {};
					Reflect.setField(tData, element.name, Date.now());
					try {
						app.db.update(table, tData, "`id`=" + app.db.quote(Std.string(id)));	
					} catch (e:Dynamic){
							messages.addError("There is an error updating the time for element: " + element.name);
					}
				}*/
			}
		}
		
		messages.addMessage((pagesMode ? "Page" : "Record") + " " + (form.getElement("__action").value == "add" ? "added." : "updated."));
		
		if (!pagesMode && !singleInstanceEdit) 
		{
			var url = "?request=cms.modules.base.Dataset";
			url += "&dataset=" + dataset;
			url += "&linkMode=" + (linkMode ? "true" : "false");
			url += "&linkToField=" + app.params.get("linkToField");
			url += "&linkTo=" + app.params.get("linkTo");
			url += "&linkValueField=" + app.params.get("linkValueField");
			url += "&linkValue=" + app.params.get("linkValue");
			url += "&autofilterByAssoc=" + app.params.get("autofilterByAssoc");
			url += "&autofilterBy=" + app.params.get("autofilterBy");
			if (siteMode) url += "&siteMode=true";
			app.redirect(url);
		}else if(pagesMode){
			var url = "?request=cms.modules.base.DatasetItem";
			url += "&pagesMode=true&action=edit&id=" + id;
			if (siteMode) url += "&siteMode=true";
			app.redirect(url);
		}else {
			//singleInstanceEdit
			var url = "?request=cms.modules.base.DatasetItem";
			url += "&dataset=" + dataset;
			url += "&id=" + id;
			url += "&autofilterByAssoc=" + app.params.get("autofilterByAssoc");
			url += "&autofilterBy=" + app.params.get("autofilterBy");
			url += "&siteMode=true";
			url += "&singleInstanceEdit=true";
		}
	}
	
	private function getOrderField():String
	{
		for (element in definition.elements)
			if (element.type == "order")
				return element.name;
		return null;
	}
	
	private function getElementMatches():List<DefinitionElementMeta>
	{
		// get fields
		var fields = app.db.request("SHOW FIELDS FROM `" + table + "`");
		fields = Lambda.map(fields, function(row:Dynamic) {	
			return row.Field;
		});
		
		// find matches
		var elements:List<DefinitionElementMeta> = Lambda.list(definition.elements);
		elements = Lambda.filter(elements, function(element:DefinitionElementMeta) {	
	
			return (Lambda.has(fields, element.name) && element.type != "hidden") || element.type == "multilink" || element.type == "linkdisplay";
		});
		
		return elements;
	}
	
	// handles upload of files
	// returns a hash representing field/new value
	// PHP ONLY
	private function uploadFiles():Hash<String>
	{
		// setup the data for deleting files ...
		var filesToDelete:List<String> = new List();
		var fieldsToWipe:List<String> = new List();
		var safeId = app.db.quote(Std.string(id));
		
		var nFilesReplaced:Int = 0;
		var nFilesAdded:Int = 0;
		var nFilesDeleted:Int = 0;
		
		// delete files we were asked to delete
		// get the list of files to delete
		var f = Web.getParams().get("form1__filesToDelete");
		var h = new Hash();
		try {
			h = Unserializer.run(f);
		}catch (e:Dynamic) {}
		// add them to the list
		for (k in h.keys()) {
			filesToDelete.add(k);
			fieldsToWipe.add(h.get(k));
		}
		
		// add new files
		var files:Hash <Hash<Dynamic>> = PhpTools.getFilesInfo();
		// data to insert into DB
		var data:Hash<String> = new Hash();
		
		for (file in files.keys())
		{
			//trace(file);
			var info:Hash<Dynamic> = files.get(file);
			var name = file.substr(form.name.length + 1);
			var filename = info.get("name");
			var randomString = Md5.encode(Date.now().toString()+Math.random());
			
			var libraryItemValue:String = app.params.get(form.name + "_" + name + "_libraryItemValue");
			
			// do we use the upload or do we use library images?
			if (app.params.get(form.name + "_" + name + "_operation") == FileUpload.OPERATION_LIBRARY && libraryItemValue != null && libraryItemValue != "") {
				// copy library item and use that!
				
				var imgRoot:String = "./res/media/galleries/";
				if (FileSystem.exists(imgRoot + libraryItemValue)) {
					
					var copyToName = randomString + libraryItemValue.substr(libraryItemValue.lastIndexOf("/") + 1);
					
					File.copy(imgRoot + libraryItemValue, PokoCms.uploadFolder + copyToName);
					data.set(name, copyToName);
					nFilesAdded++;
				}
				
			}else {
				// upload file	
				if (info.get("error") == 0) 
				{
					PhpTools.moveFile(info.get("tmp_name"), PokoCms.uploadFolder + randomString + filename);
					data.set(name, randomString + filename);
					nFilesAdded++;
				}
			}
		}

		// get old file names and add to delete list
		// data mode
		if(!pagesMode){
			// delete files linked to dataset items
			var sql:String = "SELECT ";
			var c = 0;

			for (k in data.keys())
			{
				// only add if we hadn't been asked to delete already
				if (!Lambda.has(fieldsToWipe, k)){
					sql += "`" + k + "`,";
					c++;
				}
			}
			sql = sql.substr(0, sql.length - 1);
			sql += " FROM " + table + " WHERE id=" + safeId;
			if(c > 0){
				var result = app.db.requestSingle(sql);
				for (i in Reflect.fields(result)) {
					filesToDelete.add(Reflect.field(result, i));
					nFilesAdded--;
					nFilesReplaced++;
				}
			}
		// pages mode
		}else {
			// delete files linked to pages?
			var r = app.db.requestSingle("SELECT data FROM _pages WHERE `id`=" + safeId);
			try {
				var d = (r.data != "" && r.data != null) ? Unserializer.run(r.data) : null;
				for (k in data.keys())
				{
					// only add if we hadn't been asked to delete already
					if (d != null && !Lambda.has(fieldsToWipe, k)){
						filesToDelete.add(Reflect.field(d, k));
						nFilesAdded--;
						nFilesReplaced++;
					}
				}
			}catch (e:Dynamic) {
				messages.addError("There may have been a problem updating your page." + e);
			}
		}
		
		// delete the files
		for (f in filesToDelete) {
			try {
				FileSystem.deleteFile(PokoCms.uploadFolder + f);
			}catch (e:Dynamic) {
				//TO DO: need to get this warning working properly!
				//messages.addError("Problem deleting file: " + f);
			}
		}
		
		// set fields to wipe for fields not already in data (ones already in data are newly uploaded)
		for (f in fieldsToWipe) {
			if (data.get(f) == null) {
				data.set(f, '');
				nFilesDeleted++;
			}else {
				nFilesReplaced++;
				nFilesAdded--;
			}
		}
		
		// update database
		if (nFilesAdded > 0 || nFilesReplaced > 0 || nFilesDeleted > 0){
			messages.addMessage("Files: " + nFilesAdded + " added, " + nFilesReplaced + " replaced and " + nFilesDeleted + " deleted.");
		}
		return data;
		
		/*
		if (!pagesMode) {
			app.db.update(table, data, "`id`=" + safeId);
		} else {
			var d:Dynamic = form.getData();
			for(k in data.keys()){
				Reflect.setField(d, k, data.get(k));
			}
		
			var sdata = Serializer.run(d);
			app.db.update("_pages", { data:sdata }, "`id`=" + safeId);
		}
		*/
	}
	
	public function stringCompare(a, b)
	{
		return a.toString() == b.toString();
	}
	
	private function setupForm():Void
	{
		form = new Form("form1");
		form.addElement(new Hidden( "__action", app.params.get("action")));
		
		var elements = pagesMode ? Lambda.list(definition.elements) : getElementMatches();
		
		var element:DefinitionElementMeta;
		for (element in elements)
		{
			var value = Reflect.field(data, element.name);
			var label = element.properties.label != "" && element.properties.label != null ? element.properties.label : element.name;
			
			switch(element.type)
			{
				case "text":
					var el:Dynamic;
					if (element.properties.isMultiline) {
						el = new TextArea(element.name, label, value, element.properties.required);
						el.height = element.properties.height;
						if (el.height > 0 && el.width > 0) el.useSizeValues = true;
					}else {
						el = new Input(element.name, label, value, element.properties.required);
						if (el.width > 0) el.useSizeValues = true;
					}
					el.width = element.properties.width;
					el.addValidator(new StringValidator(element.properties.minChars != "" ? element.properties.minChars : null, 
														element.properties.maxChars != "" ? element.properties.maxChars : null, 
														element.properties.charsList, 
														element.properties.mode == "ALLOW" ? StringValidatorMode.ALLOW : StringValidatorMode.DENY));
														
					// new EReg(element.properties.regex, element.properties.regexCaseInsensitive ? "i" : ""),
					// element.properties.regexError
														
					if (Std.string(element.properties.regex).length > 0)
					{
						var reg:EReg = new EReg(element.properties.regex, (element.properties.regexCaseInsensitive == "1" ? "i" : ""));
						el.addValidator(new RegexValidator(reg, element.properties.regexError)); 
					}
					
					// print out element.properties.regexDescription so we know what we need to input (if it exists)
					
					if (element.properties.formatter != null && element.properties.formatter != "") 
						el.formatter = Type.createInstance(Type.resolveClass(element.properties.formatter), []);
						
					el.description = element.properties.description;
					form.addElement(el);
					
				case "number":
					var el = new Input(element.name, label, value, element.properties.required == "1");
					el.addValidator(new NumberValidator(element.properties.min != "" ? element.properties.min : null, 
														element.properties.max != "" ? element.properties.max : null, 
														element.properties.isInt == "1"));
					el.description = element.properties.description;
					form.addElement(el);
					
				case "image-file":
					var el:FileUpload = new FileUpload(element.name, label, value, element.properties.required);
					if (element.properties.description) el.description = element.properties.description + "<br />";
					if (element.properties.isImage) el.description += "Images Only<br />";
					if (element.properties.extList) {
						el.description += "File Types";
						el.description += (element.properties.extMode == "ALLOW") ? " Allowed: " : " Denied: ";
						var a:Array<String> = element.properties.extList.split(",");
						for (i in a) {
							el.description += i+", ";
						}
						el.description = el.description.substr(0, el.description.length - 2);
						el.description += "<br />";
					}
					if (element.properties.minSize && element.properties.maxSize) {
						el.description += "Size: "+element.properties.minSize+"Kb - "+element.properties.maxSize+"Kb";
					}else {
						if (element.properties.minSize) el.description += "Min Size: " + element.properties.minSize + "Kb";
						if (element.properties.maxSize) el.description += "Max Size: " + element.properties.maxSize + "Kb";
					}
					
					el.showUpload = (element.properties.uploadType == "0" || element.properties.uploadType == "1");
					el.showLibrary = (element.properties.uploadType == "0" || element.properties.uploadType == "2");
					
					el.libraryViewThumb = (element.properties.libraryView == "0" || element.properties.libraryView == "1");
					el.libraryViewList = (element.properties.libraryView == "0" || element.properties.libraryView == "2");
					
					var t = StringTools.trim(element.properties.showOnlyLibraries);
					if (t != "") el.showOnlyLibraries = t.split(":");		
					
					form.addElement(el);
					
				case "date":
					//var d:Date = (value != "" && value != null) ? cast value : Date.now();	
					var d:Date = (value != "" && value != null) ? cast value : null;	
					if (element.properties.currentOnAdd == "1" && (form.getElement("__action").value == "add" || app.params.get("action") == "add"))
						d = Date.now();
						
					//d = null;
									
					var el = new DateSelector(element.name, label, d, element.properties.required);
					if (element.properties.restrictMin == "1") el.minOffset = element.properties.minOffset;
					if (element.properties.restrictMax == "1") el.maxOffset = element.properties.maxOffset;
					
					// need to add min / max to date validation
					var dateMode = switch ( element.properties.mode )
					{
						case "DATE":
							DateTimeMode.date;
						case "TIME":
							DateTimeMode.time;
						default:
							DateTimeMode.dateTime;
					}
					
					el.mode = dateMode;
					//el.addValidator( new DateTimeValidator( dateMode ) );
					
					el.description = element.properties.description;
					form.addElement(el);
					
				case "location":
					var el = new LocationSelector( element.name, label, value, element.properties.required );
					
					el.description = element.properties.description;
					
					el.defaultLocation = element.properties.defaultLocation;
					el.popupWidth = element.properties.popupWidth;
					el.popupHeight = element.properties.popupHeight;
					el.searchAddress = element.properties.searchAddress;
					
					form.addElement(el);
				
				case "richtext-tinymce":
					var el = new Richtext(element.name, label, value, element.properties.required);
					
					if (element.properties.mode) el.mode = Type.createEnum(Type.resolveEnum("poko.form.elements.RichtextMode"), element.properties.mode);
					if (element.properties.width != "") el.width = Std.parseInt(element.properties.width);
					if (element.properties.height != "") el.height = Std.parseInt(element.properties.height);
					if (element.properties.content_css != "" && element.properties.content_css != null) el.content_css = element.properties.content_css;
					
					el.description = element.properties.description;
					form.addElement(el);
					
				case "richtext-wym":
					var el = new RichtextWym(element.name, label, value, element.properties.required);
					
					if (element.properties.width != "") el.width = Std.parseInt(element.properties.width);
					if (element.properties.height != "") el.height = Std.parseInt(element.properties.height);
					if (element.properties.allowImages != "") el.allowImages = element.properties.allowImages;
					if (element.properties.allowTables != "") el.allowTables = element.properties.allowTables;
					if (element.properties.editorStyles != "") el.editorStyles = element.properties.editorStyles;
					el.containersItems = (element.properties.containersItems != "" && element.properties.containersItems != null) ? element.properties.containersItems : "{'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'}";
					el.classesItems = (element.properties.classesItems != "" && element.properties.classesItems != null) ? element.properties.classesItems : "";
					
					el.description = element.properties.description;
					form.addElement(el);					
				
				case "read-only":
					var el = new Readonly(element.name, label, value, element.properties.required);
					el.description = element.properties.description;	
					form.addElement(el);
					
				case "bool":
					var options = new List();
					var trueLable = element.properties.labelTrue != "" ? element.properties.labelTrue : "true";
					var falseLable = element.properties.labelFalse != "" ? element.properties.labelFalse : "false";
					
					options.add( { key:"1", value:trueLable } );
					options.add( { key:"0", value:falseLable } );
					
					//var el = new RadioGroup(element.name, label, options, value, "1", false);
					var el = new RadioGroup(element.name, label, options, value, element.properties.defaultValue, false);
					el.description = element.properties.description;
					form.addElement(el);
					
					jsBind.queueCall("setupShowHideElements", [el.name, element.properties.showHideFields , value, element.properties.showHideValue]);
				
				case "association":
					var fieldLabelSelect = element.properties.fieldLabel;
					if (element.properties.fieldSql != null && element.properties.fieldSql != "") {
						fieldLabelSelect = "("+element.properties.fieldSql+")";
					}
				
					var assocData = app.db.request("SELECT `" + element.properties.field + "` as value, "+ fieldLabelSelect +" as label FROM `" + element.properties.table + "`");
					assocData = Lambda.map(assocData, function(value) {
						return { key:value.value, value:value.label };
					});
					
					// set the auto filter on the element
					if (autoFilterValue == element.name) value = autoFilterByAssocValue;
					
					var el = new Selectbox(element.name, label, assocData, value, element.properties.required);
					el.description = element.properties.description;
					form.addElement(el);
				
				case "multilink":

					var sql = ""; 
					sql += "SELECT `" + element.properties.field + "` as 'key', 		";
					sql += "       `" + element.properties.fieldLabel + "` as 'value' 	";
					sql += "  FROM `" + element.properties.table + "`					";

					var linkData = app.db.request(sql);
					
					var selectedData = new Array();
					
					if (app.params.get("action") != "add")
					{
						var sql = "";
						sql += "SELECT `" + element.properties.linkField2 + "` as 'link' 	";
						sql += "  FROM `" + element.properties.link + "`";
						sql += " WHERE `" + element.properties.linkField1 + "`=" + app.db.quote(Std.string(id));
						
						var result = app.db.request(sql);
						
						for (row in result)
							selectedData.push(Std.string(row.link));
					}
					var el = new CheckboxGroup(element.name, label, linkData, selectedData);
					if (element.properties.formatter != null && element.properties.formatter != "") 
						el.formatter = Type.createInstance(Type.resolveClass(element.properties.formatter), []);
						
					el.description = element.properties.description;
					form.addElement(el);
					
				case "keyvalue":
					var el:KeyValueInput = new KeyValueInput(element.name, label, value, element.properties);
					el.minRows = element.properties.minRows;
					el.maxRows = element.properties.maxRows;
					el.description = element.properties.description;
					if (el.minRows > 0 || el.maxRows > 0) el.description += " <br />";
					if (el.minRows > 0) el.description += " <br /><b>Minimum Rows</b>: " + el.minRows;
					if (el.maxRows > 0) el.description += " <br /><b>Maximum Rows</b>: "+el.maxRows;
					form.addElement(el);
					
				case "linkdisplay":
					
					var el = new LinkTable(element.name, label, element.properties.table, table, id, null, null, "class=\"resizableFrame\"");
					el.description = element.properties.description;
					form.addElement(el);
					
				case "post-add-current-time":
					var el = new Readonly(element.name, label, value, element.properties.required);
					el.description = element.properties.description;	
					form.addElement(el);
					
				case "enum":
					var types = app.db.requestSingle("SHOW COLUMNS FROM `"+table+"` LIKE \""+element.properties.name+"\"");
					
					var s:String = Reflect.field(types, "Type");
					var items = s.substr(6, s.length - 8).split("','");
					
					var data:List<KeyVal> = new List();
					for (item in items)
						data.add( { key:item, value:item } );
					
					if (value == null || value == "") value = items[0];
					
					var el = new RadioGroup( element.name, label, data, value);
					el.description = element.properties.description;
					form.addElement(el);
			}
			
		}
		
		if (linkMode)
		{
			form.addElement(new Hidden(app.params.get("linkToField"), app.params.get("linkTo")));
			form.addElement(new Hidden(app.params.get("linkValueField"), app.params.get("linkValue")));
		}
		if (app.params.get("siteMode") == "true")
			form.addElement(new Hidden("siteMode", "true"));
		
		var aF = app.params.get("autofilterBy");
		if (aF != null && aF != "") form.addElement(new Hidden("autofilterBy", aF));
		var aFA = app.params.get("autofilterByAssoc");
		if (aFA != null && aFA != "") form.addElement(new Hidden("autofilterByAssoc", aFA));
		
		var submitButton = new Button( "__submit", app.params.get("action") == "add" ? "Add" : "Update", null, ButtonType.SUBMIT);
		
		var keyValJsBinding = jsBindings.get("site.cms.modules.base.js.JsKeyValueInput"); 
		if (keyValJsBinding != null){
			submitButton.attributes = "onClick=\"" + jsBind.getCall("flushWymEditors", []) + "; return(" + keyValJsBinding.getCall("flushKeyValueInputs", []) +");\"";
		}else {
			submitButton.attributes = "onClick=\"return(" + jsBind.getCall("flushWymEditors", []) + ");\"";
		}
		
		form.addElement(submitButton);
		
		if (app.params.get("action") == "add" && linkMode) {
			var cancelButton = new Button("__cancel", "Cancel", "Cancel", ButtonType.BUTTON);
			form.addElement(cancelButton);
		}
		
		form.populateElements();
	}
	
}