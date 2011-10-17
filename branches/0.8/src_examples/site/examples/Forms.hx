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
import poko.form.elements.Checkbox;
import poko.form.elements.ImageUpload;
import poko.form.elements.Input;
import poko.form.elements.KeyVal;
import poko.form.elements.LocationSelector;
import poko.form.elements.RadioGroup;
import poko.form.elements.RichtextWym;
import poko.form.elements.Selectbox;
import poko.form.elements.Button;
import poko.form.elements.TextArea;
import poko.form.Form;
import poko.form.validators.StringValidator;
import poko.utils.html.ScriptType;
import site.cms.common.PageData;
import site.examples.components.Navigation;
import site.examples.templates.DefaultTemplate;

class Forms extends DefaultTemplate
{
	public var form1:Form;
	public var form2:Form;
	
	override public function main()
	{
		scripts.addExternal(ScriptType.js, "js/cms/wymeditor/jquery.wymeditor.pack.js");
		scripts.addExternal(ScriptType.js, "js/cms/wym_editor_browse.js");
		
		form1 = new Form("form1");
		
		form1.addElement(new Input("name", "Your Name"));
		form1.addElement(new Selectbox("gender", "Gender"));
	
		var gender = form1.getElementTyped("gender", Selectbox);
		gender.addOption( { key:"M", value:"Male" } );
		gender.addOption( { key:"F", value:"Female" } );
		
		var checkbox = new Checkbox("newsletter", "Sign up for newsletter?", false);
		form1.addElement(checkbox);
		
		var radioGroup = new RadioGroup("age", "Age", null);
		radioGroup.addOption("15-18", "15 - 18 years");
		radioGroup.addOption("18-25", "18 - 25 years");
		radioGroup.addOption("25-35", "25 - 35 years");
		radioGroup.addOption("45+", "over 45 years");
		form1.addElement(radioGroup);
		
		var textarea = new TextArea("specialRequests", "Special Requests", null);
		textarea.height = 60;
		textarea.useSizeValues = true;
		form1.addElement(textarea);
		
		var editor = new RichtextWym("editor", "Editor", null);
		editor.width = 400;
		editor.height = 200;
		form1.addElement(editor);
		
		var location = new LocationSelector("location", "Location (Lat/Long)", "");
		location.popupWidth = 500;
		location.popupHeight = 500;
		location.searchAddress = true;
		form1.addElement(location);
		
		form1.setSubmitButton(form1.addElement(new Button("submit", "Submit")));	
		form1.populateElements();
		
		form2 = new Form("form2");
		var el = new Input("name", "Your Name", null, true);
		el.addValidator(new StringValidator(3, 10));
		form2.addElement(el);
		form2.addElement(new Selectbox("doStuff", "Do Stuff?"));
		form2.setSubmitButton(form2.addElement(new Button("submit", "Submit")));
		
		var doStuff = form2.getElementTyped("doStuff", Selectbox);
		doStuff.addOption( { key:"1", value:"Yes" } );
		doStuff.addOption( { key:"0", value:"No" } );
		form2.populateElements();
	}
}