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
import poko.form.Validator;
import poko.utils.ListData;

class DateSelector extends FormElement
{
	public var maxOffset:Int;
	public var minOffset:Int;
	
	public function new(name:String, label:String, ?value:Date, ?required:Bool=false, ?validators:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.attributes = attibutes;
		
		maxOffset = null;
		minOffset = null;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		var sb = new StringBuf();
		
		var s:StringBuf = new StringBuf();
		
		s.add("<input type=\"text\" name=\"" + n + "\" id=\"" + n + "\" value=\"" + value + "\" /> \n");
		
		s.add("<script type=\"text/javascript\">			\n");
		s.add("		$(function() {							\n");
		
		var maxOffsetStr = minOffset != null ? ", minDate: '-" + minOffset + "m'" : "";
		var minOffsetStr = maxOffset != null ? ", maxDate: '+" + maxOffset + "m'" : "";
		
		s.add("			$(\"#"+n+"\").datepicker({ dateFormat: 'yy-mm-dd' "+minOffsetStr+maxOffsetStr+" });		\n");
		s.add("		}); 									\n");
		s.add("</script> 									\n");

		
		return s.toString();
	}
	
	public function toString() :String
	{
		return render();
	}
}



/*var date:Date = cast value;
		var year = date.getFullYear();
		var month = date.getMonth();
		var day = date.getDate();
		
		var l = new List();
		var s = "";
		
		var elYear = new Selectbox(form, "1", ListData.getYears(1990, 2000, true), Std.string(year), false, "");
		var elMonth = new Selectbox(form, "2", ListData.getMonths(), Std.string(year), false);
		var elDay = new Selectbox(form, "3", ListData.getDays() , Std.string(year), false);
		
		form.addElement(name + "[]", elYear);
		form.addElement(name + "[]", elMonth);
		form.addElement(name + "[]", elDay);
		
		s += elYear.toString(); 
		s += elMonth.toString(); 
		s += elDay.toString(); 
		
		form.initElements();
		*/