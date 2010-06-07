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
import poko.form.elements.Input;
import poko.form.elements.Selectbox;
import poko.form.elements.Button;
import poko.form.Form;
import poko.form.validators.StringValidator;
import poko.Request;
import site.cms.common.PageData;
import site.examples.components.Navigation;
import site.examples.templates.DefaultTemplate;

class Forms extends DefaultTemplate
{
	public var form1:Form;
	public var form2:Form;
	
	override public function main()
	{
		form1 = new Form("form1");
		form1.addElement(new Input("name", "You Name"));
		form1.addElement(new Selectbox("gender", "Gender"));
		form1.setSubmitButton(form1.addElement(new Button("submit", "Submit")));
		form1.populateElements();
		
		var gender = form1.getElementTyped("gender", Selectbox);
		gender.addOption( { key:"male", value:"male" } );
		gender.addOption( { key:"female", value:"female" } );
		
		
		
		form2 = new Form("form2");
		var el = new Input("name", "You Name",null,true);
		el.addValidator(new StringValidator(3, 10, "abcdefg"));
		form2.addElement(el);
		form2.addElement(new Selectbox("gender", "Gender"));
		form2.setSubmitButton(form2.addElement(new Button("submit", "Submit")));
		form2.populateElements();
		
		var gender = form2.getElementTyped("gender", Selectbox);
		gender.addOption( { key:"male", value:"male" } );
		gender.addOption( { key:"female", value:"female" } );
		
	}
}