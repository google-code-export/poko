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

import poko.form.elements.Button;
import poko.form.elements.Input;
import poko.form.elements.Selectbox;
import poko.utils.PhpTools;
import php.Lib;
import php.Web;
import templo.Loader;

class Form
{
	public var id:String;
	public var name:String;
	public var action:String;
	public var method:FormMethod;
	public var elements:List<FormElement>;

	public var fieldsets:Hash<FieldSet>;
	
	public var forcePopulate:Bool;
	public var submitButton:FormElement;
	
	private var extraErrors:List<String>;
	
	public var requiredClass:String;
	public var requiredErrorClass:String;
	public var invalidErrorClass:String;
	public var labelRequiredIndicator:String;
	
	public var defaultClass : String;
	
	public function new(name:String, ?action:String, ?method:FormMethod) 
	{
		requiredClass = "formRequired";
		requiredErrorClass = "formRequiredError";
		invalidErrorClass = "formInvalidError";
		labelRequiredIndicator = " *";
		
		this.forcePopulate = false;
		this.id = this.name = name;
		this.action = action;
		this.method = (method == null) ? FormMethod.POST : method;
		
		elements = new List();
		extraErrors = new List();
		
		fieldsets = new Hash();
		addFieldset("__default", new FieldSet("__default", "Default", false));
	}
	
	public function addElement(element:FormElement, ?fieldSetKey:String = "__default"):FormElement
	{
		element.form = this;
		elements.add(element);
		
		// add it to a group if requested
		if (fieldSetKey != null && fieldsets.exists(fieldSetKey)) {
			fieldsets.get(fieldSetKey).elements.add(element);
		}
		
		return element;
	}
	
	public function setSubmitButton(el:FormElement):FormElement
	{
		return submitButton = el;
	}
	
	public function addFieldset(fieldSetKey:String, fieldSet:FieldSet)
	{
		fieldSet.form = this;
		fieldsets.set(fieldSetKey, fieldSet);
	}
	
	public function getFieldsets():Hash<FieldSet>
	{
		return fieldsets;
	}
	
	public function getLabel( elementName : String ) : String
	{
		return getElement( elementName ).getLabel();
	}
	
	public function getElement(name:String):FormElement
	{
		for (element in elements) 
		{
			if (element.name == name) 
				return element;
		}
		
		throw "Cannot access Form Element: '" + name + "'";
		
		return null;
	}
	
	public function getElementTyped<T>(name:String, type:Class<T>):T
	{
		var o:T = cast(getElement(name));
		return o;
	}
	
	public function getData():Dynamic
	{
		var data:Dynamic = {};
		for (element in getElements())
		{
			Reflect.setField(data, element.name, element.value);
		}
		return data;
	}
	
	public function populateElements():Dynamic
	{
		var element:FormElement;
		for (element in getElements()){
			element.populate();
		}
	}
	
	public function clearData():Dynamic
	{
		var element:FormElement;
		for (element in getElements()){
			element.value = null;
		}
	}
	
	public function getOpenTag():String
	{
		return "<form id=\""+id+"\" name=\"" + name + "\" method=\""+ method +"\" action=\""+ action +"\" enctype=\"multipart/form-data\" >";
	}
	
	public function getCloseTag():String
	{
		return "<input type=\"hidden\" name=\""+name+"_formSubmitted\" value=\"true\" /></form>";
	}
	
	public function isValid():Bool
	{
		var valid:Bool = true;
		
		for (element in getElements()) 
			if (!element.isValid()) 
				valid = false;
		
		return valid;
	}	
	
	public function addError(error:String)
	{
		extraErrors.add(error);
	}
	
	public function getErrorsList():List<String>
	{
		isValid();
		
		var errors:List<String> = new List();
		
		for(e in extraErrors)
			errors.add(e);
		
		for (element in getElements())
			for (error in element.getErrors())
				errors.add(error);
		
		return errors;
	}
	
	public function getElements():List<FormElement>
	{
		return elements;
	}
	
	public function isSubmitted():Bool
	{
		return Web.getParams().get(name + "_formSubmitted") == "true";
	}
	
	public function getSubmittedValue():String
	{
		return Web.getParams().get(name + "_formSubmitted");
	}	
	
	public function getErrors():String
	{
		if (!isSubmitted())	
			return "";
				
		var s:StringBuf = new StringBuf();
		var errors = getErrorsList();
		
		if (errors.length > 0) 
		{
			s.add("<ul class=\"formErrors\" >");
			for (error in errors)
			{
				s.add("<li>"+error+"</li>");
			}
			s.add("</ul>");
		}
		return s.toString();
	}
	
	public function getPreview()
	{
		var s:StringBuf = new StringBuf();
		s.add(getOpenTag());
		
		if (isSubmitted())
			s.add(getErrors());
		
		s.add("<table>\n");
		
		for (element in getElements()) 
			if(element != submitButton) s.add("\t"+element.getPreview()+"\n");

		if (submitButton != null) {
			submitButton.form = this;
			s.add(submitButton.getPreview());
		}
			
		s.add("</table>\n");
		s.add(getCloseTag());
		
		return s.toString();
	}
}

class FieldSet
{
	public var name:String;
	public var form:Form;
	public var label:String;
	public var visible:Bool;
	public var elements:List<FormElement>;
	
	public function new(?name:String = "", ?label:String = "", ?visible:Bool = true)
	{
		this.name = name;
		this.label = label;
		this.visible = visible;
		
		elements = new List();
	}
	
	public function getOpenTag()
	{
		return "<fieldset id=\""+form.name+"_"+name+"\" name=\""+form.name+"_"+name+"\" class=\""+(visible?"":"fieldsetNoDisplay")+"\" ><legend>" + label + "</legend>";
	}
	
	public function getCloseTag()
	{
		return "</fieldset>";
	}
}

enum FormMethod
{
	GET;
	POST;
}