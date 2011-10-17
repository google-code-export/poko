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

package site.examples;

import poko.form.elements.Button;
import poko.form.elements.DateDropdowns;
import poko.form.elements.DateInput;
import poko.form.elements.DateSelector;
import poko.form.elements.EmbeddedVideoOptions;
import poko.form.Form;
import poko.utils.html.ScriptType;
import site.cms.common.DateTimeMode;
import site.examples.templates.DefaultTemplate;

class Dates extends DefaultTemplate
{
	public var form:Form;
	
	override public function main()
	{		
		//scripts.addExternal(ScriptType.css, "css/datepicker.css" );
		scripts.addExternal(ScriptType.css, "css/cms/ui-lightness/jquery-ui-1.7.2.custom.css" );
		//scripts.addExternal(ScriptType.js, "js/date.js" );
		
		scripts.addExternal(ScriptType.js, "js/cms/jquery-ui-1.7.2.custom.min.js" );
		//scripts.addExternal(ScriptType.js, "js/jquery.datePicker.js" );
		//scripts.addExternal(ScriptType.js, "js/initDatePicker.js" );
		
		form = new Form("form");
		var d = new DateSelector("date", "Date", Date.now());
		d.mode = DateTimeMode.dateTime;
		form.addElement(d);
		
		var d2 = new DateInput("date2", "Date 2", Date.now());
		form.addElement(d2);
		
		//var video = new EmbeddedVideoOptions("vimeo", "Vimeo", EmbeddedVideoService.vimeo);
		//video.vimeo.
		
		//var d3 = new DateDropdowns("date3", "Date 3", Date.now() );
		//form.addElement(d3);
		
		form.setSubmitButton(form.addElement(new Button("submit", "Submit")));
		
		form.populateElements();
		
		if ( form.isSubmitted() && form.isValid() )
		{
			var data = form.getData();
		}
	}
}