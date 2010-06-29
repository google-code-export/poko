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

package poko.form;

import poko.Poko;
import poko.js.JsBinding;
import poko.utils.PhpTools;
import php.Web;

class FormElement
{		
	public var form:Form;
	public var name:String;
	public var label:String;
	public var description:String;
	public var value:Dynamic;
	public var required:Bool;
	public var errors:List<String>;
	public var attributes:String;
	public var active:Bool;
	public var validators:List<Validator>;
	public var cssClass:String;
	
	public function new() 
	{
		active = true;
		errors = new List();
		validators = new List();
	}
	
	public function isValid():Bool
	{
		errors.clear();
		
		if (active == false)
			return true;
		
		if (value == "" && required) 
		{
			errors.add("Please enter '" + ((label != null && label != "") ? label : name) + "'");
			return false;
		} 
		else if(value != "")
		{
			if (!validators.isEmpty())
			{
				var pass:Bool = true;
				for (validator in validators)
				{
					if (!validator.isValid(value)) {
						//for (error in validator.errors)
						//	errors.add(error);
						
						pass = false;
					}
				}
				if (!pass) return false;
			}
			
			return true;
		}
		return true;
	}
	
	public function addValidator(validator:Validator)
	{
		validators.add(validator);
	}
	
	public function bindEvent(event:String, method:String, params:Array<Dynamic>, ?isMethodGlobal:Bool=false) 
	{
		//Poko.instance.request.jsBindings.add(new JsBinding(form.name + "_" + name, event, method, params, isMethodGlobal));
	}
	
	public function populate():Void
	{
		var n = form.name + "_" + name;
		var v = Poko.instance.params.get(n);
		
		if (v != null) value = v;
	}
	
	public function getErrors():List<String>
	{
		isValid();
		
		for (val in validators)
			for(err in val.errors)
				errors.add(label + " : " + err);
		
		return errors;
	}
	
	public function render():String
	{
		return value;
	}
	
	public function getPreview():String
	{
		return "<tr><td>" + getLabel() + "</td><td>" + this.render() + "<td></tr>";
	}
	
	public function getType():String
	{
		return Std.string(Type.getClass(this));
	}
	public function getLabel():String
	{
		var n = form.name + "_" + name;
		
		var css = "";
		var requiredSet = false;
		if (required) {
			css = form.labelRequiredClass;
			if (form.isSubmitted() && required && value == "") {
				css = form.labelRequiredErrorClass;
				requiredSet = true;
			}
		}
		if(!requiredSet && form.isSubmitted() && !isValid()){
			css = form.labelErrorClass;
		}
		
		return "<label for=\"" + n + "\" class=\""+css+"\">" + label +(if(required) form.labelRequiredIndicator) +"</label>";
	}
}
