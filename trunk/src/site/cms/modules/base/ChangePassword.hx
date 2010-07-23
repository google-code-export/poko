/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tarwin Stroh-Spijer <tarwin@touchmypixel.com>
 * Contributors: Tony Polinelli
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

package site.cms.modules.base;

import poko.form.elements.Button;
import poko.form.elements.CheckboxGroup;
import poko.form.elements.Hidden;
import poko.form.elements.Input;
import poko.form.elements.RadioGroup;
import poko.form.Form;
import poko.utils.Tools;
import haxe.Md5;
import php.Web;
import site.cms.modules.base.Users;
import site.cms.templates.CmsTemplate;

class ChangePassword extends CmsTemplate
{
	public var userData:List<Dynamic>;
	public var action:String;
	
	public var form1:Form;
	public var heading:String;
	
	public function new() 
	{
		super();
	}
	
	override public function main():Void
	{	
		super.main();
		
		action = app.params.get("form1_action");
		
		heading = "Change Password";
		
		setupForm1();
		if (form1.isSubmitted() && form1.isValid())
			process();
		
		navigation.setSelected("Password");
	}
	
	private function process():Void
	{
		switch(action)
		{
			case "change":
				var d:Dynamic = form1.getData();
				var oldPassword = app.db.requestSingle("SELECT password FROM _users WHERE id='" + user.id + "'").password;
				if (Md5.encode(d.passwordOld) == oldPassword) {
					if (d.passwordNew1 == d.passwordNew2) {
						app.db.update("_users", { password: Md5.encode(d.passwordNew1) }, "id='" + user.id + "'");
						messages.addMessage("Password updated.");
						form1.clearData();
					}else {
						messages.addError("You did not enter the new password the same twice. Please try again.");
					}
				}else {
					messages.addError("Your old password was not correct.");
				}
		}
	}
	
	private function setupForm1():Void
	{
		form1 = new Form("form1");
		
		var pass1 = new Input("passwordOld", "Old Password", null, true);
		pass1.password = true;
		form1.addElement(pass1);
		
		var pass2 = new Input("passwordNew1", "New Password", null, true);
		pass2.password = true;
		form1.addElement(pass2);
		
		var pass3 = new Input("passwordNew2", "New Password (Again)", null, true);
		pass3.password = true;
		form1.addElement(pass3);
		
		form1.addElement(new Hidden("action", "change"));
		form1.addElement(new Button("submit", "Update", "Update"));
		
		form1.populateElements();
	}
}