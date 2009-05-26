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

package site.cms.modules.base.formElements;


import poko.Application;
import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;
import poko.js.JsBinding;
import site.cms.common.InputTypeDef;
import haxe.Serializer;

class LinkTable extends FormElement
{
	public var linkTable:String;
	public var linkTo:String;
	public var linkValue:Int;
	
	public function new(name:String, label:String, linkTable:String, linkTo:String, linkValue:Int, ?validatorsKey:Array<Validator>, ?validatorsValue:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.attributes = attibutes;
		this.linkTable = linkTable;
		this.linkTo = linkTo;
		this.linkValue = linkValue;
	}
	
	override public function render():String
	{
		var str = "";
		
		var linkDefId = site.cms.common.Definition.tableToDefinitionId(linkTable);
		var linkToField = null;
		var linkValueField = null;
		var linkdef = new site.cms.common.Definition(linkDefId);
		for (el in linkdef.elements)
		{
			if (el.type == "link-to")
			{
				linkToField = el.name;
				break;
			}
		}
		
		for (el in linkdef.elements)
		{
			if (el.type == "link-value")
			{
				linkValueField = el.name;
				break;
			}
		}
		
		if (linkValue == null)
		{
			str += "Please edit link tables after adding item.";
		}
		else if(linkValueField == null || linkToField == null)
		{
			if (linkValueField == null) str += "Could not find a 'link-to' in dataset definition<br/>";
			if (linkToField==null) str += "Could not find both 'link-value' in dataset definition<br/>";
		} 
		else 
		{
			var url = "?request=cms.modules.base.Dataset&dataset=" + linkDefId + "&linkMode=true";
			url += "&linkToField=" + linkToField;
			url += "&linkTo=" + linkTo;
			url += "&linkValueField=" + linkValueField;
			url += "&linkValue=" + linkValue;
			
			str += "<iframe width=\"630\" height=\"300\" src=\""+url+"\">";
			str += name;
			str += "</iframe>";
		}
		
		
		return str;
	}
	
	public function toString():String
	{
		return render();
	}
}