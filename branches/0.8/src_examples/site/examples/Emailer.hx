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

import touchmypixel.mail.SimpleSmtp;
import touchmypixel.mail.SimpleMail;
import poko.form.validators.EmailValidator;
import poko.form.elements.Button;
import poko.form.elements.TextArea;
import poko.form.elements.Input;
import poko.form.Form;
import poko.form.validators.StringValidator;
import site.cms.common.PageData;
import site.examples.components.Navigation;
import site.examples.templates.DefaultTemplate;

class Emailer extends DefaultTemplate
{
	public var form:Form;
	public var message:String;
	
	override public function main()
	{	
		form = new Form("emailer");
		
		var el = new Input("toName", "To (name)", null, true);
		el.addValidator(new StringValidator(1, 255));
		form.addElement(el);
		
		el = new Input("toEmail", "To (email)", null, true);
		el.addValidator(new EmailValidator());
		form.addElement(el);
		
		var el = new Input("fromName", "From (name)", null, true);
		el.addValidator(new StringValidator(1, 255));
		form.addElement(el);
		
		el = new Input("fromEmail", "From (email)", null, true);
		el.addValidator(new EmailValidator());
		form.addElement(el);
		
		var el = new TextArea("message", "Message", null, true);
		el.addValidator(new StringValidator(10, 255));
		form.addElement(el);
		
		form.addElement(new Button("submit", "Send Email"));
		
		form.populateElements();
		
		if (form.isSubmitted() && form.isValid()) {

			var s = new SimpleSmtp("mail.touchmypixel.com", 21, "testies+touchmypixel.com", "pass");
			var m = new SimpleMail();
			m.to = "tarwin@gmail.com";
			m.from = "tarwin@gmail.com";
			//m.to = SimpleMail.ename(form.getValueOf('toName'), form.getValueOf('toEmail'));
			//m.from = SimpleMail.ename(form.getValueOf('fromName'), form.getValueOf('fromEmail'));
			m.subject = "Test Email";
			m.setHtml("<h1>hello world</h1><p>hello ...</p>");
			
			message = "Email sent to "+form.getElement("toEmail").value+".";
			
			try {
				s.sendMail(m);
			}catch(e:Dynamic){
				message = e;
			}
		}
	}
}