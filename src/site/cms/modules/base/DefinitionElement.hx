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

import poko.form.elements.LocationSelector;
import poko.form.elements.Readonly;
import poko.form.elements.TextArea;
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
	
	override public function init()
	{
		super.init();
		
		head.js.add("js/cms/jquery-ui-1.7.2.custom.min.js");
		head.css.add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");		
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDefinitionElement");
		
		remoting.addObject("api", { getListData:getListData, getListData2:getListData2 } );
	}
	
	public function getListData(table):Array<String>
	{
		var result = app.db.request("SHOW FIELDS FROM `" + table + "`");
		var arr = [];
		for (item in result)
			arr.push(item.Field);
			
		return arr;
	}
	
	public function getListData2(table, table2):Array<Array<String>>
	{
		var out = [];
		
		var result = app.db.request("SHOW FIELDS FROM `" + table + "`");
		var arr = [];
		for (item in result)
			arr.push(item.Field);
		out.push(arr);
		
		result = app.db.request("SHOW FIELDS FROM `" + table2 + "`");
		arr = [];
		for (item in result)
			arr.push(item.Field);
		out.push(arr);		
			
		return out;
	}	
	
	/** end remoting */
	
	
	override public function main()
	{
		super.main();
		
		definition = new Definition(app.params.get("id"));
		
		meta = definition.getElement(app.params.get("definition"));
		
		setupForm();
		
		if (form.isSubmitted()) 
		{
			update();
			if (pagesMode)
			{
				Web.redirect("?request=cms.modules.base.Definition&id=" + app.params.get("id") + "&pagesMode=true");
			} else {
				Web.redirect("?request=cms.modules.base.Definition&id=" + app.params.get("id"));
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
		var datatypes:List<Dynamic> = ListData.flatArraytoList(["text", "number", "bool", "image-file", "richtext-tinymce", "richtext-wym", "date", "association", "keyvalue", "read-only", "order", "link-to", "link-value", "hidden", "enum", "post-sql-value", "location"]);
		
		// for bool selectors
		var yesno = new List();
		yesno.add( { value:"Yes", key:"1" } );
		yesno.add( { value:"No", key:"0" } );
		
		var truefalse = new List();
		truefalse.add( { value:"True", key:"1" } );
		truefalse.add( { value:"False", key:"0" } );		
		
		// for image text selectors
		var imagefile = new List();
		imagefile.add( { value:"Image", key:"1" } );
		imagefile.add( { value:"File", key:"0" } );
		
		// for image type selector
		var uploadTypeList = new List();
		uploadTypeList.add( { value:"Both", key:"0" } );
		uploadTypeList.add( { value:"Upload", key:"1" } );
		uploadTypeList.add( { value:"Media Library", key:"2" } );
		
		// for image type selector
		var libraryView = new List();
		libraryView.add( { value:"Both", key:"0" } );
		libraryView.add( { value:"Thumbs", key:"1" } );
		libraryView.add( { value:"List", key:"2" } );
		
		// DB Data types
		var dataType = new List();
		dataType.add( { value:"VARCHAR", key:"varchar" } );
		dataType.add( { value:"TEXT", key:"text" } );
		dataType.add( { value:"BOOL", key:"bool" } );
		dataType.add( { value:"INT", key:"int" } );
		dataType.add( { value:"FLOAT", key:"float" } );
		dataType.add( { value:"DOUBLE", key:"double" } );
		
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
				assocFields = app.db.request("SHOW FIELDS FROM `" + selectedAssocTable + "`");
				assocFields = Lambda.map(assocFields, function(field) {
					return { key:field.Field, value:field.Field };
				});
			} catch (e:Dynamic) { 
				assocFields = null;
			}
		}
		
		var localFields:List<Dynamic> = null;
		try {
			localFields = app.db.request("SHOW FIELDS FROM `" + definition.table + "`");
			localFields = Lambda.map(localFields, function(field) {
				return { key:field.Field, value:field.Field };
			});
		} catch (e:Dynamic) { 
			localFields = null;
		}		
		
		var selectedMultiTable = data.table;
		var multiFields:List<Dynamic> = null;
		if (selectedMultiTable != null)
		{
			try {
				multiFields = app.db.request("SHOW FIELDS FROM `" + selectedMultiTable + "`");
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
				multiLinkFields = app.db.request("SHOW FIELDS FROM `" + selectedMultilinkTable + "`");
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

		if (meta.type == "linkdisplay" || meta.type == "multilink" || meta.type == "listformatter") 
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
			form.addElement(new TextArea("att_description", "Description", data.description, false, null, "class=\"resizable\" style=\"width: 200px; height: 3em;\""));
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
		form.addElement(new Input( "def_text_minChars", "Min Chars", data.minChars), "properties");
		form.addElement(new Input( "def_text_maxChars", "Max Chars", data.maxChars), "properties");
		form.addElement(new Input( "def_text_charsList", "Chars List", data.charsList), "properties");
		form.addElement(new RadioGroup( "def_text_mode", "Chars List Mode", charsModeOptions, data.mode, "ALLOW", false), "properties");
		form.addElement(new Input( "def_text_regex", "Regex", data.regex), "properties");
		form.addElement(new Input( "def_text_regexError", "Regex Error message", data.regexError), "properties");
		form.addElement(new Input( "def_text_regexDescription", "Regex Description", data.regexDescription), "properties");
		form.addElement(new RadioGroup( "def_text_regexCaseInsensitive", "Regex Case Insensitive", yesno, data.regexCaseInsensitive, "0", false), "properties");
		form.addElement(new RadioGroup( "def_text_required", "Required", yesno, data.required, "0", false), "properties");
		var input:Input = new Input("def_text_formatter", "Formatter Class", data.formatter, false);
		input.width = 400;
		input.useSizeValues = true;
		form.addElement(input, "properties");
		
		form.addElement(new Input( "def_number_min", "Min", data.min), "properties");
		form.addElement(new Input( "def_number_max", "Max", data.max), "properties");
		form.addElement(new RadioGroup( "def_number_isInt", "isInt", numberType, data.isInt, "0", false), "properties");
		form.addElement(new RadioGroup( "def_number_required", "Required", yesno, data.required, "0", false), "properties");
		
		form.addElement(new Input( "def_bool_labelTrue", "Label 'true'", data.labelTrue), "properties");
		form.addElement(new Input( "def_bool_labelFalse", "Label 'false'", data.labelFalse), "properties");
		form.addElement(new RadioGroup( "def_bool_defaultValue", "Default Value", truefalse, data.defaultValue, "0", false), "properties");
		form.addElement(new Input( "def_bool_showHideFields", "Hide Field(s)", data.showHideFields), "properties");
		form.addElement(new RadioGroup( "def_bool_showHideValue", "Hide on...", truefalse, data.showHideValue, "0", false), "properties");
		
		/*
		form.addElement("def_image-file_setAspect", new RadioGroup( form, "Set Aspect", yesno, data.setAspect, "0", false));
		form.addElement("def_image-file_aspectW", new Input( form, "Aspect Width", data.aspectW, "0"));
		form.addElement("def_image-file_aspectH", new Input( form, "Aspect Height", data.aspectW, "0"));
		*/
		form.addElement(new RadioGroup( "def_image-file_required", "Required", yesno, data.required, "0", false), "properties");
		form.addElement(new RadioGroup( "def_image-file_isImage", "Type", imagefile, data.isImage, "0", false), "properties");
		form.addElement(new Input( "def_image-file_extList", "Ext (jpg,gif,png)", data.extList, false), "properties");
		form.addElement(new RadioGroup( "def_image-file_extMode", "Ext Mode", charsModeOptions, data.extMode, "ALLOW", false), "properties");
		form.addElement(new Input( "def_image-file_minSize", "Min size (Kb)", data.minSize, false), "properties");
		form.addElement(new Input( "def_image-file_maxSize", "Max size (Kb)", data.maxSize, false), "properties");
		form.addElement(new RadioGroup( "def_image-file_uploadType", "Location", uploadTypeList, data.uploadType, "0", false), "properties");
		form.addElement(new Input( "def_image-file_showOnlyLibraries", "Only Libraries", data.showOnlyLibraries, false), "properties");
		form.addElement(new RadioGroup( "def_image-file_libraryView", "Library View", libraryView, data.libraryView, "0", false), "properties");
		
		//form.addElement("def_date_selector", new DateSelector( form, "Select Date", Date.now()));
		form.addElement(new RadioGroup( "def_date_currentOnAdd", "Current date on add?", yesno, data.currentOnAdd, "0", false), "properties");
		form.addElement(new RadioGroup( "def_date_restrictMin", "Restrict Min", yesno, data.restrictMin, "0", false), "properties");
		form.addElement(new Input( "def_date_minOffset", "Min Offset (-months)", data.minOffset, false), "properties");
		form.addElement(new RadioGroup( "def_date_restrictMax", "Restrict Max", yesno, data.restrictMax, "0", false), "properties");
		form.addElement(new Input( "def_date_maxOffset", "Min Offset (+months)", data.maxOffset, false), "properties");
		form.addElement(new RadioGroup( "def_date_required", "Required", yesno, data.required, "0", false), "properties");
		//form.addElement(new Selectbox( "def_date_asdf", "asdf", yesno, data.required, "0", false), "properties");
		
		var dateModes = new List();
		dateModes.add( { value:"Date & Time", key:"DATETIME" } );
		dateModes.add( { value:"Date", key:"DATE"} );
		dateModes.add( { value:"Time", key:"TIME"} );
		form.addElement(new Selectbox( "def_date_mode", "Mode", dateModes, data.mode, false, ""), "properties");
		
		//form.addElement(new Input( "def_location_defaultLocation", "Default Location", data.defaultLocation, false), "properties");
		form.addElement(new LocationSelector( "def_location_defaultLocation", "Default Location", data.defaultLocation, false), "properties");
		form.addElement(new Input( "def_location_popupWidth", "Popup Width", data.popupWidth, false), "properties");
		form.addElement(new Input( "def_location_popupHeight", "Popup Height", data.popupHeight, false), "properties");
		form.addElement(new RadioGroup( "def_location_searchAddress", "Allow searching addresses?", yesno, data.searchAddress, "1", false), "properties");
		
		var rtf = new List();
		rtf.add( { key:"SIMPLE", value:"Simple" } );
		rtf.add( { key:"FORMAT", value:"Simple w/ Format"} );
		rtf.add( { key:"SIMPLE_TABLES", value:"Simple w/ tables"} );
		rtf.add( { key:"ADVANCED", value:"Advanced" } );
		
		form.addElement(new Selectbox( "def_richtext-tinymce_mode", "Mode", rtf, data.mode, false,""), "properties");
		form.addElement(new Input( "def_richtext-tinymce_width", "Width", data.width), "properties");
		form.addElement(new Input( "def_richtext-tinymce_height", "Height", data.height), "properties");
		form.addElement(new Input( "def_richtext-tinymce_content_css", "CSS file", data.content_css), "properties");
		form.addElement(new RadioGroup( "def_richtext-tinymce_required", "Required", yesno, data.required, "0", false), "properties");
		
		form.addElement(new Input( "def_richtext-wym_width", "Width", data.width), "properties");
		form.addElement(new Input( "def_richtext-wym_height", "Height", data.height), "properties");
		form.addElement(new RadioGroup( "def_richtext-wym_required", "Required", yesno, data.required, "0", false), "properties");
		form.addElement(new RadioGroup( "def_richtext-wym_allowTables", "Allow Tables", yesno, data.allowTables, "0", false), "properties");
		form.addElement(new RadioGroup( "def_richtext-wym_allowImages", "Allow Images", yesno, data.allowImages, "1", false), "properties");
		form.addElement(new RadioGroup( "def_richtext-wym_allowImages", "Allow Images", yesno, data.allowImages, "1", false), "properties");
		form.addElement(new RadioGroup( "def_richtext-wym_useFtp", "Use FTP?", yesno, data.useFtp, "1", false), "properties");
		form.addElement(new Input( "def_richtext-wym_ftpDirectory", "FTP directory", data.ftpDirectory), "properties");
		
		var t = new TextArea("def_richtext-wym_editorStyles", "Editor Styles", data.editorStyles, false, null);
		t.width = 500;
		t.height = 200;
		t.useSizeValues = true;
		form.addElement(t, "properties");
		
		// {'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'}, {'name': 'H1', 'title': 'Heading_1', 'css': 'wym_containers_h1'}
		if (data.containersItems == null)
		{
			data.containersItems = "";
			data.containersItems += "{'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'}, \n";
			data.containersItems += "{'name': 'H1', 'title': 'Heading_1', 'css': 'wym_containers_h1'}";
		}
		t = new TextArea("def_richtext-wym_containersItems", "Containers", data.containersItems, false, null);
		t.width = 500;
		t.height = 200;
		t.useSizeValues = true;
		form.addElement(t, "properties");
		
		// {'name': 'date', 'title': 'PARA: Date', 'expr': 'p'}, {'name': 'hidden-note', 'title': 'PARA: Hidden note', 'expr': 'p[@class!="important"]'}
		t = new TextArea("def_richtext-wym_classesItems", "Classes", data.classesItems, false, null);
		t.width = 500;
		t.height = 200;
		t.useSizeValues = true;
		form.addElement(t, "properties");		
		
		form.addElement(new Input( "def_keyvalue_minRows", "Min Rows", data.minRows), "properties");
		form.addElement(new Input( "def_keyvalue_maxRows", "Max Rows", data.maxRows), "properties");
		
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
		
		var assoc4:Input = new Input( "def_association_fieldSql", "Label SQL", data.fieldSql);
		assoc4.useSizeValues = true;
		assoc4.width = 400;
		form.addElement(assoc4, "properties");
		
		form.addElement(new RadioGroup( "def_association_showAsLabel", "Show as Label?", yesno, data.showAsLabel, "0", false), "properties");
		
		// multilink
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
		
		// link display
		var assoc:Selectbox = new Selectbox("def_linkdisplay_table", "Link Table", tableList, data.table);
		form.addElement(assoc, "properties");
		
		// list formatter
		var i:Input = new Input("def_listformatter_formatter", "Formatter Class", data.formatter);
		form.addElement(i, "properties");
		
		// post SQL value --------------------------------------------------------------------------
		form.addElement(new Hidden("def_post-sql-value_updateKeyTable", definition.table), "properties");
		
		var postSqlValue1:Selectbox = new Selectbox("def_post-sql-value_table", "With Table", tableList, data.table);
		postSqlValue1.onChange = jsBind.getRawCall("onChangeSelectbox(this)");
		form.addElement(postSqlValue1, "properties");
		
		var postSqlValue2:Selectbox = new Selectbox( "def_post-sql-value_updateTo", "Update to", assocFields, data.updateTo);
		form.addElement(postSqlValue2, "properties");
		
		/*var postSqlValue4:Input = new Input( "def_post-sql-value_updateSql", "Update to SQL", data.updateSql);
		postSqlValue4.useSizeValues = true;
		postSqlValue4.width = 400;
		form.addElement(postSqlValue4, "properties");*/
		
		var postSqlValue3:Selectbox = new Selectbox( "def_post-sql-value_updateKey", "Local Key", localFields, data.updateKey);
		form.addElement(postSqlValue3, "properties");
		// --------------------------------------------------------------------------
		
		//
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
			if (element.name == "att_description")
				data.description = element.value;			
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

