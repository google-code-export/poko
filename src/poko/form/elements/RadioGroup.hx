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

class RadioGroup extends FormElement 
{
	public var data:List<Dynamic>;
	public var selectMessage:String;
	public var labelLeft:Bool;
	public var verticle:Bool;
	public var labelRight:Bool;
	
	public function new(name:String, label:String, data:List<Dynamic>, ?selected:String, ?defaultValue:String, ?verticle:Bool=true, ?labelRight:Bool=true) 
	{
		super();
		this.name = name;
		this.label = label;
		this.data = data;
		this.value = selected != null ? selected : defaultValue;
		this.verticle = verticle;
		this.labelRight = labelRight;
	}
	
	override public function render():String
	{
		var s = "";
		var n = form.name + "_" +name;
		
		var c = 0;
		if (data != null)
		{
			for (row in data)
			{
				var radio = "<input type=\"radio\" name=\""+n+"\" id=\""+n+c+"\" value=\"" + row.value + "\" " + (row.value == Std.string(value) ? "checked":"") +" />\n";
				var label = "<label for=\"" + n+c + "\" >" + row.key  +"</label>";
				
				s += labelRight ? radio + " "+label+" ": label+" "+radio+" ";
				if (verticle) s += "<br />";
				c++;
			}	
		}
		
		return s;
	}
	
	public function toString() :String
	{
		return render();
	}
}