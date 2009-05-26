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

import poko.form.elements.Readonly;
import poko.js.JsBinding;
import site.cms.common.Definition;
import site.cms.common.DefinitionElementMeta;
import poko.form.elements.CheckboxGroup;
import poko.form.elements.DateSelector;
import haxe.Serializer;
import haxe.Unserializer;
import poko.form.elements.Button;
import poko.form.elements.Hidden;
import poko.form.elements.Input;
import poko.form.elements.RadioGroup;
import poko.form.elements.Selectbox;
import poko.form.FormElement;
import poko.utils.ListData;
import poko.utils.PhpTools;
import poko.utils.StringTools2;
import php.Exception;
import php.Session;
import php.Sys;
import php.Web;
import poko.form.Form;
import site.cms.common.Tools;
import site.cms.modules.base.Definitions;

class DefinitionElement extends DefinitionsBase
{
	public var action:String;
	public var table:String;
	private var elements:List<FormElement>;
	private var fieldsets:Hash<FieldSet>;
	
	private var form:Form;
	private var metaData:Hash<String>;
	private var form2:Form;
	
	public var definition:Definition;
	public var meta:DefinitionElementMeta;
	
	private var typeSelector:Dynamic;
	
	public var jsBind:JsBinding;
	
	public function new() 
	{
		super();
	}
	
	
	/** remoting */
	
	override public function pre()
	{
		super.pre();
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDefinitionElement");
		
		remoting.addObject("api", { getListData:getListData } );
	}
	
	public function getListData(table):Array<String>
	{
		var result = application.db.request("SHOW FIELDS FROM `" + table + "`");
		var arr = [];
		for (item in result)
			arr.push(item.Field);
			
		return arr;
	}
	
	/** end remoting */
	
	
	override public function main()
	{
		super.main();
		
		definition = new Definition(application.params.get("id"));
		
		meta = definition.getElement(application.params.get("definition"));
		
		setupForm();
		
		if (form.isSubmitted()) 
		{
			update();
			if (pagesMode)
			{
				Web.redirect("?request=cms.modules.base.Definition&id=" + application.params.get("id") + "&pagesMode=true");
			} else {
				Web.redirect("?request=cms.modules.base.Definition&id=" + application.params.get("id"));
			}
			//Sys.exit(1);
		}
		
		setupForm();
		
		setupLeftNav();
	}
	
	private function update():Void
	{
		meta.properties = getFormData();
		
		meta.name = meta.properties.name;
		meta.type = meta.properties.type;
		meta.label = meta.properties.label;
		meta.showInList = meta.properties.showInList;
		
		meta.showInFiltering = meta.properties.showInFiltering;
		meta.showInOrdering = meta.properties.showInOrdering;
		
		definition.save();
	}
	
	public function setupForm() 
	{
		form = new Form("form");
		
		var data:Dynamic = meta.properties;
		
		// for type selector
		var datatypes:List<Dynamic> = ListData.flatArraytoList(["text", "number", "bool", "image", "richtext-tinymce", "richtext-wym", "date", "association", "keyvalue", "read-only", "order", "link-to", "link-value", "hidden"]);
		
		// for bool selectors
		var yesno = new List();
		yesno.add( { key:"Yes", value:"1" } );
		yesno.add( { key:"No", value:"0" } );
		
		// DB Data types
		var dataType = new List();
		dataType.add( { key:"VARCHAR", value:"varchar" } );
		dataType.add( { key:"TEXT", value:"text" } );
		dataType.add( { key:"BOOL", value:"bool" } );
		dataType.add( { key:"INT", value:"int" } );
		dataType.add( { key:"FLOAT", value:"float" } );
		dataType.add( { key:"DOUBLE", value:"double" } );
		
		// table in db for linking
		var tableList:List<Dynamic> = Tools.getDBTables();
		tableList = Lambda.map(tableList, function(table) {
			return { key:table, value:table };
		});
		
		var selectedAssocTable = data.table;
		var assocFields:List<Dynamic> = null;
		if (selectedAssocTable != null)
		{
			try {
				assocFields = application.db.request("SHOW FIELDS FROM `" + selectedAssocTable + "`");
				assocFields = Lambda.map(assocFields, function(field) {
					return { key:field.Field, value:field.Field };
				});
			} catch (e:Dynamic) { 
				assocFields = null;
			}
		}
		
		var selectedMultiTable = data.table;
		var multiFields:List<Dynamic> = null;
		if (selectedMultiTable != null)
		{
			try {
				multiFields = application.db.request("SHOW FIELDS FROM `" + selectedMultiTable + "`");
				multiFields = Lambda.map(multiFields, function(field) {
					return { key:field.Field, value:field.Field };
				});
			} catch (e:Dynamic) { 
				multiFields = null;
			}
		}
		
		var selectedMultilinkTable = data.link;
		var multiLinkFields:List<Dynamic> = null;
		if (selectedMultilinkTable != null)
		{
			try {
				multiLinkFields = application.db.request("SHOW FIELDS FROM `" + selectedMultilinkTable + "`");
				multiLinkFields = Lambda.map(multiLinkFields, function(field) {
					return { key:field.Field, value:field.Field };
				});
			} catch (e:Dynamic) { 
				multiLinkFields = null;
			}
		}
		
		// for text mode field
		var charsModeOptions = new List();
		charsModeOptions.add( { key:"Allow", value:"ALLOW" } );
		charsModeOptions.add( { key:"Deny", value:"DENY" } );
		
		// for number type field
		var numberType = new List();
		numberType.add( { key:"Float", value:"0" } );
		numberType.add( { key:"Int", value:"1" } );
		
		elements = new List();
		form.addElement(new Readonly( "att_name", "Field", meta.name));
		
		if (meta.type == "linkdisplay" || meta.type == "multilink"  ) 
		{
			// Sepcial display for links
			form.addElement(new Input( "att_label", "Label", data.label));
			typeSelector = new Readonly("type", "Type", meta.type);
			form.addElement(typeSelector);	
		} 
		else
		{
			// general display
			form.addElement(new Input( "att_label", "Label", data.label));
			typeSelector = new Selectbox( "type", "Type", datatypes, meta.type, true, "");
			form.addElement(typeSelector);
			form.addElement(new RadioGroup( "att_showInList", "Show in List?", yesno, meta.showInList ? "1" : "0", "0", false));	
			form.addElement(new RadioGroup( "att_showInFiltering", "Show in filter?", yesno, meta.showInFiltering ? "1" : "0", "0", false));	
			form.addElement(new RadioGroup( "att_showInOrdering", "Enable ordering?", yesno, meta.showInOrdering ? "1" : "0", "0", false));	
		}
		
		form.addFieldset("properties", new FieldSet("propertiesFieldset", "Properties", true));
		
		form.addElement(new RadioGroup( "def_text_isMultiline", "Multiline?", yesno, data.isMultiline, "0", false), "properties");	
		form.addElement(new Input( "def_text_width", "Width", data.width), "properties");
		form.addElement(new Input( "def_text_height", "Height", data.height), "properties");
		form.addElement(new Input( "def_text_minChars", "MinChars", data.minChars), "properties");
		form.addElement(new Input( "def_text_maxChars", "MaxChars", data.maxChars), "properties");
		form.addElement(new Input( "def_text_charsList", "Chars List", data.charsList), "properties");
		form.addElement(new RadioGroup( "def_text_mode", "Chars List Mode", charsModeOptions, data.mode, "ALLOW", false), "properties");
		form.addElement(new Input( "def_text_regex", "Regex", data.regex), "properties");
		form.addElement(new Input( "def_text_regexError", "Regex Error message", data.regexError), "properties");
		form.addElement(new Input( "def_text_regexDescription", "Regex Description", data.regexDescription), "properties");
		form.addElement(new RadioGroup( "def_text_regexCaseInsensitive", "Regex Case Insensitive", yesno, data.regexCaseInsensitive, "0", false), "properties");
		form.addElement(new RadioGroup( "def_text_required", "Required", yesno, data.required, "0", false), "properties");
		
		form.addElement(new Input( "def_number_min", "Min", data.min), "properties");
		form.addElement(new Input( "def_number_max", "Max", data.max), "properties");
		form.addElement(new RadioGroup( "def_number_isInt", "isInt", numberType, data.isInt, "0", false), "properties");
		form.addElement(new RadioGroup( "def_number_required", "Required", yesno, data.required, "0", false), "properties");
		
		form.addElement(new Input( "def_bool_labelTrue", "Label 'true'", data.labelTrue), "properties");
		form.addElement(new Input( "def_bool_labelFalse", "Label 'false'", data.labelFalse), "properties");
		
		/*
		form.addElement("def_image_setAspect", new RadioGroup( form, "Set Aspect", yesno, data.setAspect, "0", false));
		form.addElement("def_image_aspectW", new Input( form, "Aspect Width", data.aspectW, "0"));
		form.addElement("def_image_aspectH", new Input( form, "Aspect Height", data.aspectW, "0"));
		*/
		form.addElement(new RadioGroup( "def_image_required", "Required", yesno, data.required, "0", false), "properties");
		
		//form.addElement("def_date_selector", new DateSelector( form, "Select Date", Date.now()));
		form.addElement(new RadioGroup( "def_date_restrictMin", "Restrict Min", yesno, data.restrictMin, "0", false), "properties");
		form.addElement(new Input( "def_date_minOffset", "Min Offset (-months)", data.minOffset, false), "properties");
		form.addElement(new RadioGroup( "def_date_restrictMax", "Restrict Max", yesno, data.restrictMax, "0", false), "properties");
		form.addElement(new Input( "def_date_maxOffset", "Min Offset (+months)", data.maxOffset, false), "properties");
		form.addElement(new RadioGroup( "def_date_required", "Required", yesno, data.required, "0", false), "properties");
		
		var rtf = new List();
		rtf.add( { key:"Simple", value:"SIMPLE" } );
		rtf.add( { key:"Simple w/ Format", value:"FORMAT"} );
		rtf.add( { key:"Simple w/ tables", value:"SIMPLE_TABLES"} );
		rtf.add( { key:"Advanced", value:"ADVANCED" } );
		
		form.addElement(new Selectbox( "def_richtext-tinymce_mode", "Mode", rtf, data.mode, false,""), "properties");
		form.addElement(new Input( "def_richtext-tinymce_width", "Width", data.width), "properties");
		form.addElement(new Input( "def_richtext-tinymce_height", "Height", data.height), "properties");
		form.addElement(new Input( "def_richtext-tinymce_content_css", "CSS file", data.content_css), "properties");
		form.addElement(new RadioGroup( "def_richtext-tinymce_required", "Required", yesno, data.required, "0", false), "properties");
		
		form.addElement(new Input( "def_richtext-wym_width", "Width", data.width), "properties");
		form.addElement(new Input( "def_richtext-wym_height", "Height", data.height), "properties");
		form.addElement(new RadioGroup( "def_richtext-wym_required", "Required", yesno, data.required, "0", false), "properties");
		
		form.addFieldset("key", new FieldSet("def_keyvalue_keyFieldset", "Key"));
		form.addElement(new Input("def_keyvalue_keyLabel", "Label", data.keyLabel), "key");
		form.addElement(new RadioGroup( "def_keyvalue_keyIsMultiline", "Multiline?", yesno, data.keyIsMultiline, "0", false), "key");
		form.addElement(new Input( "def_keyvalue_keyWidth", "Width", data.keyWidth), "key");
		form.addElement(new Input( "def_keyvalue_keyHeight", "Height", data.keyHeight), "key");		
		form.addElement(new Input( "def_keyvalue_keyMinChars", "MinChars", data.keyMinChars), "key");
		form.addElement(new Input( "def_keyvalue_keyMaxChars", "MaxChars", data.keyMaxChars), "key");
		form.addElement(new Input( "def_keyvalue_keyCharsList", "Chars List", data.keyCharsList), "key");
		form.addElement(new RadioGroup( "def_keyvalue_keyNode", "Chars List Mode", charsModeOptions, data.keyMode, "ALLOW", false), "key");
		form.addElement(new Input( "def_keyvalue_keyRegex", "Regex", data.keyRegex), "key");
		form.addElement(new Input( "def_keyvalue_keyRegexError", "Regex Error message", data.keyRegexError), "key");
		form.addElement(new Input( "def_keyvalue_keyDescription", "Regex Description", data.keyDescription), "key");
		form.addElement(new RadioGroup( "def_keyvalue_keyRegexCaseInsensitive", "Regex Case Insensitive", yesno, data.keyRegexCaseInsensitive, "0", false), "key");
		form.addElement(new RadioGroup( "def_keyvalue_keyRequired", "Required", yesno, data.keyRequired, "0", false), "key");
		
		form.addFieldset("value", new FieldSet("def_keyvalue_valueFieldset", "Value"));
		form.addElement(new Input("def_keyvalue_valueLabel", "Label", data.valueLabel), "value");
		form.addElement(new RadioGroup( "def_keyvalue_valueIsMultiline", "Multiline?", yesno, data.valueIsMultiline, "0", false), "value");
		form.addElement(new Input( "def_keyvalue_valueWidth", "Width", data.valueWidth), "value");
		form.addElement(new Input( "def_keyvalue_valueHeight", "Height", data.valueHeight), "value");		
		form.addElement(new Input( "def_keyvalue_valueMinChars", "MinChars", data.valueMinChars), "value");
		form.addElement(new Input( "def_keyvalue_valueMaxChars", "MaxChars", data.valueMaxChars), "value");
		form.addElement(new Input( "def_keyvalue_valueCharsList", "Chars List", data.valueCharsList), "value");
		form.addElement(new RadioGroup( "def_keyvalue_valueMode", "Chars List Mode", charsModeOptions, data.valueMode, "ALLOW", false), "value");
		form.addElement(new Input( "def_keyvalue_valueRegex", "Regex", data.valueRegex), "value");
		form.addElement(new Input( "def_keyvalue_valueRegexError", "Regex Error message", data.valueRegexError), "value");
		form.addElement(new Input( "def_keyvalue_valueDescription", "Regex Description", data.valueDescription), "value");
		form.addElement(new RadioGroup( "def_keyvalue_valueRegexCaseInsensitive", "Regex Case Insensitive", yesno, data.valueRegexCaseInsensitive, "0", false), "value");
		form.addElement(new RadioGroup( "def_keyvalue_valueRequired", "Required", yesno, data.valueRequired, "0", false), "value");
		//
		
		var assoc1:Selectbox = new Selectbox( "def_association_table", "Table", tableList, data.table);
		assoc1.onChange = jsBind.getRawCall("onChangeSelectbox(this)");
		
		form.addElement(assoc1, "properties");
		
		var assoc2:Selectbox = new Selectbox( "def_association_field", "Field", assocFields, data.field);
		form.addElement(assoc2, "properties");
		
		var assoc3:Selectbox = new Selectbox( "def_association_fieldLabel", "Label", assocFields, data.fieldLabel);
		form.addElement(assoc3, "properties");
		
		form.addElement(new RadioGroup( "def_association_showAsLabel", "Show as Label?", yesno, data.showAsLabel, "0", false), "properties");
		
		//
		var multiTable:Selectbox = new Selectbox( "def_multilink_table", "With Table", tableList, data.table);
		multiTable.onChange =  jsBind.getRawCall("onChangeSelectbox(this)");
		form.addElement(multiTable, "properties");
		
		var multiField:Selectbox = new Selectbox( "def_multilink_field", "With Field", multiFields, data.field);
		form.addElement(multiField, "properties");
		
		var multiLabel:Selectbox = new Selectbox( "def_multilink_fieldLabel", "With Label", multiFields, data.fieldLabel);
		form.addElement(multiLabel, "properties");
		
		//
		var multilinkTable:Selectbox = new Selectbox( "def_multilink_link", "Link Table", tableList, data.link);
		//
		multilinkTable.onChange =  jsBind.getRawCall("onChangeSelectbox(this)");
		form.addElement(multilinkTable, "properties");
		
		var multiLinkField1:Selectbox = new Selectbox( "def_multilink_linkField1", "Link Field 1", multiLinkFields, data.linkField1);
		form.addElement(multiLinkField1, "properties");
		
		var multiLinkField2:Selectbox = new Selectbox( "def_multilink_linkField2", "Link Field 2", multiLinkFields, data.linkField2);
		form.addElement(multiLinkField2, "properties");
		
		var assoc:Selectbox = new Selectbox("def_linkdisplay_table", "link Table", tableList, data.table);
		form.addElement(assoc, "properties");
		
		
		form.addFieldset("submit", new FieldSet("__submit", "__submit", false));
		
		form.setSubmitButton(form.addElement(new Button( "submit", "Submit"), "submit"));
		
		form.populateElements();
		
		fieldsets = form.getFieldsets();
	}
	
	private function getFormData():Dynamic
	{
		var data:Dynamic = {};
		var type = typeSelector.value;
		data.type = type;
		
		for (element in form.getElements()) 
		{
			if (element.name == "att_name") 
				data.name = element.value;
			if (element.name == "att_label")
				data.label = element.value;
			if (element.name == "att_showInList")
				data.showInList = (element.value == "1") ? 1 : 0;
				
			if (element.name == "att_showInFiltering")
				data.showInFiltering = (element.value == "1") ? 1 : 0;
			if (element.name == "att_showInOrdering")
				data.showInOrdering = (element.value == "1") ? 1 : 0;				
			
			
			if (element.name.indexOf("def_" + type ) != -1)
			{
				var attr = element.name.split("_").pop();
				Reflect.setField(data, attr, element.value);
			}
		}
			
		return data;
	}
}

