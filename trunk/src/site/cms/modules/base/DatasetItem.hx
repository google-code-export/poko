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

import poko.form.validators.DateValidator;
import poko.utils.JsBinding;
import haxe.Md5;
import site.cms.common.DefinitionElementMeta;
import poko.form.elements.Button;
import poko.form.elements.CheckboxGroup;
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
	
	public function new() 
	{
		super();
	}
	
	override public function pre()
	{
		super.pre();
		
		head.js.add("js/cms/tiny_mce/tiny_mce.js");
		head.js.add("js/cms/jquery-ui-1.7.1.custom.min.js");
		
		head.css.add("css/cms/cupertino/jquery-ui-1.7.1.custom.css");
		
		// change layout for link view
		if (linkMode)
		{
			head.css.add("css/cms/miniView.css");
			this.template_file = "site/cms/templates/CmsTemplate_mini.mtt";
		}
	}
	
	override public function main()
	{
		data = { };
		
		id = Std.parseInt(application.params.get('id'));
		dataset = Std.parseInt(application.params.get("dataset"));
		isOrderingEnabled = false;
		
		
		if (!pagesMode)
		{
			// Dataset Mode
			
			definition = new Definition(dataset);
			label = definition.name;
			table = definition.table;
			data = application.db.requestSingle("SELECT * FROM `" + table + "` WHERE `id`=" + application.db.cnx.quote(Std.string(id)));
			
			orderField = getOrderField();
			isOrderingEnabled = orderField != null;
			
		} else {
			
			// Pages mode
			
			var result = application.db.requestSingle("SELECT * FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id AND p.id=" + application.db.cnx.quote(Std.string(id)));
			label = page = result.name;
			
			data = result.data != "" ? cast Unserializer.run(result.data) : {};
				
			definition = new Definition(result.definitionId);
		}		
		
		navigation.pageHeading += " (" + label + ")";
		
		setupForm();
		
		if (form.isSubmitted() && form.isValid()) processForm();
		
		setupForm();
		
		setupLeftNav();
	}
	
	private function processForm():Void
	{
		var data = form.getData();
		
		switch(form.getElement("__action").value)
		{
			case "add":
				if (isOrderingEnabled)
				{
					var result = application.db.requestSingle("SELECT MAX(`" + orderField + "`) as 'order' FROM `"+table+"`");
					Reflect.setField(data, orderField, Std.string(result.order + 1));
				}
				application.db.insert(table, data);
				id = application.db.cnx.lastInsertId();
			case "edit": 
			
				if (pagesMode)
				{
					var sdata = Serializer.run( form.getData());
					application.db.update("_pages", {data:sdata}, "`id`=" + application.db.cnx.quote(Std.string(id)));
				} else {
					application.db.update(table, data, "`id`=" + application.db.cnx.quote(Std.string(id)));
				}		
		}

		// update multilink linkTos
		var elements = pagesMode ? Lambda.list(definition.elements) : getElementMatches();
		for (element in elements)
		{
			if (element.type == "multilink")
			{
				// delete existing links
				application.db.delete(element.properties.link, "`" + element.properties.linkField1 + "`=" + application.db.cnx.quote(Std.string(id)));
				
				// insert new links
				for (check in cast(Reflect.field(data, element.name), Array<Dynamic>))
				{
					var d:Dynamic = { };
					Reflect.setField(d, element.properties.linkField1, id);
					Reflect.setField(d, element.properties.linkField2, check);
					
					application.db.insert(element.properties.link, d); 
				}
			}
		}
		
		// upload files
		uploadFiles();
		
		application.messages.addMessage((pagesMode ? "Page" : "Dataset") + " updated.");
		
		if (!pagesMode) 
		{
			var url = "?request=cms.modules.base.Dataset";
			url += "&dataset=" + dataset;
			url += "&linkMode=" + (linkMode ? "true" : "false");
			url += "&linkToField=" + application.params.get("linkToField");
			url += "&linkTo=" + application.params.get("linkTo");
			url += "&linkValueField=" + application.params.get("linkValueField");
			url += "&linkValue=" + application.params.get("linkValue");
			application.redirect(url);
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
		var fields = application.db.request("SHOW FIELDS FROM `" + table + "`");
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
	
	private function uploadFiles():Void
	{
		var files:Hash <Hash<String>> = PhpTools.getFilesInfo();
		
		for (file in files.keys())
		{
			var info:Hash<Dynamic> = files.get(file);
			var name = file.substr(form.name.length + 1);
			var filename = info.get("name");
			filename = Md5.encode(Date.now().toString()) + filename;
			
			if (info.get("error") == 0) 
			{
				PhpTools.moveFile(info.get("tmp_name"), application.uploadFolder + "/" + filename);
				
				var data:Dynamic = {};
				Reflect.setField(data, name, filename);
				
				if(!pagesMode){
					application.db.update(table, data, "`id`=" + application.db.cnx.quote(Std.string(id)));
				} else {
					var d:Dynamic = form.getData();
					Reflect.setField(d, name, filename);
					
					var sdata = Serializer.run(d);
					application.db.update("_pages", { data:sdata }, "`id`=" + application.db.cnx.quote(Std.string(id)));
				}
			}			
		}
	}
	
	private function setupForm():Void
	{
		form = new Form("form1");
		
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
					}else {
						el = new Input(element.name, label, value, element.properties.required);
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
					
					form.addElement(el);
					
				case "number":
					var el = new Input(element.name, label, value, element.properties.required == "1");
					el.addValidator(new NumberValidator(element.properties.min != "" ? element.properties.min : null, 
														element.properties.max != "" ? element.properties.max : null, 
														element.properties.isInt == "1"));
					form.addElement(el);
					
				case "image":
					form.addElement(new FileUpload(element.name, label, value, element.properties.required));
					
				case "date":
					
					var d:Date = value != "" ? cast value : Date.now();
					
					var el = new DateSelector(element.name, label, d, element.properties.required);
					if (element.properties.restrictMin == "1") el.minOffset = element.properties.minOffset;
					if (element.properties.restrictMax == "1") el.maxOffset = element.properties.maxOffset;
					
					// need to add min / max to date validation
					el.addValidator(new DateValidator());
					
					form.addElement(el);
				
				case "richtext":
					var el = new Richtext(element.name, label, value, element.properties.required);
					
					if (element.properties.mode) el.mode = Type.createEnum(Type.resolveEnum("poko.form.elements.RichtextMode"), element.properties.mode);
					if (element.properties.width != "") el.width = Std.parseInt(element.properties.width);
					if (element.properties.height != "") el.height = Std.parseInt(element.properties.height);
					if (element.properties.content_css != "" && element.properties.content_css != null) el.content_css = element.properties.content_css;
					
					form.addElement(el);
				
				case "read-only":
					form.addElement(new Readonly(element.name, label, value, element.properties.required));
				
				case "bool":
					var options = new List();
					var trueLable = element.properties.labelTrue != "" ? element.properties.labelTrue : "true";
					var falseLable = element.properties.labelFalse != "" ? element.properties.labelFalse : "false";
					
					options.add( { key:trueLable, value:"1" } );
					options.add( { key:falseLable, value:"0" } );
					
					form.addElement(new RadioGroup(element.name, label, options, value, "1", false));
				
				case "association":
					var assocData = application.db.request("SELECT `" + element.properties.field + "` as value, "+ element.properties.fieldLabel +" as label FROM `" + element.properties.table + "`");
					assocData = Lambda.map(assocData, function(value) {
						return { key:value.label, value:value.value };
					});
					form.addElement(new Selectbox(element.name, label, assocData, value, element.properties.required));
				
				case "multilink":
					
					var sql = ""; 
					sql += "SELECT `" + element.properties.field + "` as 'key', 		";
					sql += "       `" + element.properties.fieldLabel + "` as 'value' 	";
					sql += "  FROM `" + element.properties.table + "`					";
					
					var linkData = application.db.request(sql);
					
					
					var selectedData = new Array();
					
					if (application.params.get("action") != "add")
					{
						var sql = "";
						sql += "SELECT `" + element.properties.linkField2 + "` as 'link' 	";
						sql += "  FROM `" + element.properties.link + "`";
						sql += " WHERE `" + element.properties.linkField1 + "`=" + application.db.cnx.quote(Std.string(id));
						
						var result = application.db.request(sql);
						
						for (row in result)
							selectedData.push(Std.string(row.link));
					}
					var el = new CheckboxGroup(element.name, label, linkData, selectedData);
					
					form.addElement(el);
					
				case "keyvalue":
					var el = new KeyValueInput(element.name, label, value, element.properties);
					form.addElement(el);
					
				case "linkdisplay":
					var el = new LinkTable(element.name, label, element.properties.table, table, id);
					form.addElement(el);
			}
			
		}
		
		if (linkMode)
		{
			form.addElement(new Hidden(application.params.get("linkToField"), application.params.get("linkTo")));
			form.addElement(new Hidden(application.params.get("linkValueField"), application.params.get("linkValue")));
		}
		
		form.addElement(new Hidden( "__action", application.params.get("action")));
		
		var submitButton = new Button( "__submit", application.params.get("action") == "add" ? "Add" : "Update", null, ButtonType.SUBMIT);
		
		var keyValJsBinding = jsBindings.get("site.cms.modules.base.js.JsKeyValueInput"); 
		if (keyValJsBinding != null)
			submitButton.attributes = "onClick=\"return(" + keyValJsBinding.getCall("flushKeyValueInputs", []) +");\"";
		
		form.addElement(submitButton);
		
		form.populateElements();
	}
	
}