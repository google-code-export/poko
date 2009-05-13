/**
 * ...
 * @author ...
 */

package site.cms.modules.base.js;

import poko.js.JsApplication;
import poko.js.JsRequest;
import js.Dom;
import js.Lib;

class JsDefinitionElement extends JsRequest
{
	public var types:Array<String>;
	public var assocSelectBox1:js.Select;
	public var assocSelectBox2:js.Select;
	public var assocSelectBox3:js.Select;
		
	public function new() 
	{
		super();
	}
	
	/** remoting */
	
	public function onChangeSelectbox(selectBox:FormElement)
	{
		switch(selectBox.name) 
		{
			case "form_def_association_table":
				assocSelectBox1 = cast Lib.document.getElementById("form_def_association_table");
				assocSelectBox2 = cast Lib.document.getElementById("form_def_association_field");
				assocSelectBox3 = cast Lib.document.getElementById("form_def_association_fieldLabel");
			case "form_def_multilink_table":
				assocSelectBox1 = cast Lib.document.getElementById("form_def_multilink_table");
				assocSelectBox2 = cast Lib.document.getElementById("form_def_multilink_field");
				assocSelectBox3 = cast Lib.document.getElementById("form_def_multilink_fieldLabel");
			case "form_def_multilink_link":
				assocSelectBox1 = cast Lib.document.getElementById("form_def_multilink_link");
				assocSelectBox2 = cast Lib.document.getElementById("form_def_multilink_linkField1");
				assocSelectBox3 = cast Lib.document.getElementById("form_def_multilink_linkField2");
		}
		
		for (i in 0...assocSelectBox2.options.length)
			assocSelectBox2.remove(0);
			
		for (i in 0...assocSelectBox3.options.length)
			assocSelectBox3.remove(0);
		
		assocSelectBox2.innerHTML += "<option>-- loading --</option>";
		assocSelectBox3.innerHTML += "<option>-- loading --</option>";
		
		new JQuery("button").attr("disabled", true);
		
		remoting.api.getListData.call([assocSelectBox1.value], onAssocDataLoaded);
	}
	private function onAssocDataLoaded(response:Array<String>):Void
	{
		assocSelectBox2.innerHTML = "";
		assocSelectBox3.innerHTML = "";
			
		for (item in response)
		{
			assocSelectBox2.innerHTML += "<option value=\"" + item + "\">" + item + "</option>";
			assocSelectBox3.innerHTML += "<option value=\"" + item + "\">" + item + "</option>";
		}	
		
		assocSelectBox3.selectedIndex = 1;
			
		new JQuery("button").attr("disabled", false);
	}
	
	/** end remoting */
	
	
	override public function main()
	{
		types = ["text", "number", "bool", "image", "richtext", "date", "association", "multilink", "keyvalue", "read-only", "order", "link", "hidden"];
		var types = types;
		var ths = this;
		var typeSelector:js.Select = cast Lib.document.getElementById("form_type");
		
		hideAllElements();
		
		if (typeSelector.value != "") ths.showElements(typeSelector.value);
		
		typeSelector.onchange = function(e) 
		{
			ths.hideAllElements();
			ths.showElements(typeSelector.value);
		}
	}

	function hideAllElements()
	{
		new JQuery("select[id^='form_def_'],input[id^='form_def_']").parent().parent().css("display", "none");
		new JQuery("fieldset[id^='form_def_']").css("display", "none");
	}
	
	function showElements(field:String)
	{
		if (Lib.isIE)
		{
			new JQuery("[id^='form_def_" + field + "']").parent().parent().css("display", "block");
		}else {
			new JQuery("[id^='form_def_" + field + "']").parent().parent().css("display", "table-row");
		}
		new JQuery("fieldset[id^='form_def_" + field + "']").css("display", "block");
	}
	
}