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
import php.Web;
import poko.form.Formatter;

class CheckboxGroup extends FormElement 
{
	public var data:List<Dynamic>;
	public var selectMessage:String;
	public var labelLeft:Bool;
	public var verticle:Bool;
	public var labelRight:Bool;
	
	public var formatter:Formatter;
	public var columns:Int;
	
	public function new(name:String, label:String, data:List<Dynamic>, ?selected:Array<String>, ?verticle:Bool=true, ?labelRight:Bool=true) 
	{
		super();
		this.name = name;
		this.label = label;
		this.data = data;
		this.value = selected != null ? selected : new Array();
		this.verticle = verticle;
		this.labelRight = labelRight;
		
		columns = 4;
	}
	
	override public function populate()
	{
		
		var v = Web.getParamValues(form.name + "_" + name);
		
		if (form.isSubmitted())
		{
			value = (v != null) ? v : new Array();
		} else {
			if (v != null) 
				value = v;
		}
	}
	
	override public function render():String
	{
		var s = "";
		var n = form.name + "_" +name;
		
		if (value != null)
		{
			value = Lambda.map(value, function(item) {
				return item+"";
			});
		}
		
		var c = 0;
		var array = Lambda.array(data);
		if (array != null)
		{
			var rowsPerColumn = Math.ceil(array.length / columns);
			s = "<table><tr>";
			for (i in 0...columns)
			{
				s += "<td valign=\"top\">\n";
				s += "<table>\n";
				
				for (j in 0...rowsPerColumn)
				{
					s += "<tr>";
					
					var row:Dynamic = array[c];
					
					var checkbox = "<input type=\"checkbox\" name=\""+n+"[]\" id=\""+n+c+"\" value=\"" + row.key + "\" " + (value != null ? Lambda.has(value, row.key+"") ? "checked":"":"") +" ></input>\n";
					var label;
					
					if (formatter != null)
					{
						label = "<label for=\"" + n + c + "\" >" + formatter.format(row.value)  +"</label>\n";
					} else {
						label = "<label for=\"" + n + c + "\" >" + row.value  +"</label>\n";
					}
					
					if (labelRight)
					{
						s += "<td>" + checkbox + "</td>\n";
						s += "<td>" + label + "</td>\n";
					} else {
						s += "<td>" + label + "</td>\n";
						s += "<td>" + checkbox + "</td>\n";
					}
					s += "</tr>";
					
					c++;
					
					if (c >= array.length) break;
				}
				s += "</table>";
				s += "</td>";
			}
			s += "</tr></table>\n";
			
		}
		
		return s;
	}
	
	public function toString() :String
	{
		return render();
	}
}