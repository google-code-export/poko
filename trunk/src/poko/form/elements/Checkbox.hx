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


package poko.form.elements;

import poko.form.Form;
import poko.form.FormElement;
import poko.Poko;

class Checkbox extends FormElement
{	
	public var checked : Bool;
	
	public function new(name:String, label:String, ?checked:Bool=false, ?required:Bool=false, ?attibutes:String="") 
	{
		super();
		
		this.name = name;
		this.label = label;
		this.value = checked ? "1" : "0";
		//this.value = value;
		//this.checked = checked;
		this.required = required;
		this.attributes = attibutes;
	}
	
	override public function render():String
	{		
		var n = form.name + "_" +name;
		
		//var checkedStr = ( this.checked ) ? "checked" : "";
		var checkedStr = ( value == "1" ) ? "checked" : "";
		
		return "<input type=\"checkbox\" id=\"" + n + "\" name=\"" + n + "\" class=\"" + getClasses() + "\" value=\"" + value + "\" " + checkedStr + " />";
		//return "<input type=\"checkbox\" id=\"" + n + "\" name=\"" + n + "\" class=\"" + getClasses() + "\" value=\"" + value + "\" " + checkedStr + " />";
	}
	
	public function toString() :String
	{
		return render();
	}
	
	override public function populate():Void
	{
		//super.populate();
		value = Poko.instance.params.exists( form.name + "_" + name ) ? "1" : "0";
		//checked = Poko.instance.params.exists( form.name + "_" + name );
		//checked = false;
	}
	
	override public function isValid():Bool
	{
		errors.clear();
		if ( required && !checked )
		{
			errors.add("Please check '" + ((label != null && label != "") ? label : name) + "'");
			return false;
		}
		return true;
	}
}