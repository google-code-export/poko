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
		var secondTable:String = null;
		
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
			case "form_def_post-sql-value_table":
				assocSelectBox1 = cast Lib.document.getElementById("form_def_post-sql-value_table");
				assocSelectBox2 = cast Lib.document.getElementById("form_def_post-sql-value_updateTo");
				assocSelectBox3 = cast Lib.document.getElementById("form_def_post-sql-value_updateKey");
				var inputTemp:js.Hidden = cast Lib.document.getElementById("form_def_post-sql-value_updateKeyTable");
				secondTable = inputTemp.value;
		}
		
		for (i in 0...assocSelectBox2.options.length)
			assocSelectBox2.remove(0);
			
		for (i in 0...assocSelectBox3.options.length)
			assocSelectBox3.remove(0);
		
		assocSelectBox2.innerHTML += "<option>-- loading --</option>";
		assocSelectBox3.innerHTML += "<option>-- loading --</option>";
		
		new JQuery("button").attr("disabled", true);
		
		if (secondTable != null) {
			remoting.api.getListData2.call([assocSelectBox1.value, secondTable], onAssocDataLoaded2);
		}else {
			remoting.api.getListData.call([assocSelectBox1.value], onAssocDataLoaded);
		}
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
	
	private function onAssocDataLoaded2(response:Array<Array<String>>):Void
	{
		assocSelectBox2.innerHTML = "";
		assocSelectBox3.innerHTML = "";
		
		for (item in response[0])
		{
			assocSelectBox2.innerHTML += "<option value=\"" + item + "\">" + item + "</option>";
		}
		for (item in response[1])
		{
			assocSelectBox3.innerHTML += "<option value=\"" + item + "\">" + item + "</option>";
		}
			
		new JQuery("button").attr("disabled", false);
	}	
	
	/** end remoting */
	
	
	override public function main()
	{
		types = ["text", "number", "bool", "image", "richtext-wym", "richtext-tinymce", "date", "association", "multilink", "keyvalue", "read-only", "order", "link", "hidden"];
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
		new JQuery("select[id^='form_def_'],input[id^='form_def_'],textarea[id^='form_def_']").parent().parent().css("display", "none");
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