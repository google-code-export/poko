/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tarwin Stroh-Spijer <tarwin@touchmypixel.com>
 * Contributors: Tony Polinelli
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

package poko.form.elements.custom;

import poko.Application;
import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;
import poko.utils.JsBinding;
import site.cms.common.InputTypeDef;
import haxe.Serializer;

class KeyValueInput extends FormElement
{
	public var properties:Dynamic;
	private var jsBind:JsBinding;
	
	public function new(name:String, label:String, ?value:String, ?properties:Dynamic, ?validatorsKey:Array<Validator>, ?validatorsValue:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.attributes = attibutes;
		this.properties = properties;
		
		jsBind = new JsBinding("poko.form.elements.custom.JsKeyValueInput");
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		var keyLabel = properties.keyLabel == "" ? "Key" : properties.keyLabel;
		var valueLabel = properties.valueLabel == "" ? "Value" : properties.valueLabel;
		
		var keyType:InputTypeDef = { isMultiline:properties.keyIsMultiline == "1", width:Std.parseInt(properties.keyWidth), height:Std.parseInt(properties.keyHeight) };
		var valueType:InputTypeDef = { isMultiline:properties.valueIsMultiline == "1", width:Std.parseInt(properties.valueWidth), height:Std.parseInt(properties.valueHeight) };
		
		var s = "<input type=\"hidden\" name=\"" + n + "\" id=\"" + n + "\" value=\"" +value + "\" />";
		s += "<table id=\""+n+"_keyValueTable\">";
		s += "	<tr><td><label>"+keyLabel+"</label></td><td><label>"+valueLabel+"</label></td><td></td></tr>";
		s += "</table>";
		s += "<div><a href=\"#\" onClick=\"" + jsBind.getCall("addKeyValueInput", []) + "; return(false);\">add row</a></div>";
		s += "<script>" + jsBind.getCall("setupKeyValueInput", [n, properties]) + "</script>"; 
		return s;
	}
	
	public function toString():String
	{
		return render();
	}
}