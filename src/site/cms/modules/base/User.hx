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

class User extends UsersBase
{
	public var userData:List<Dynamic>;
	public var action:String;
	public var actionId:Int;
	
	public var form1:Form;
	public var heading:String;
	
	public function new() 
	{
		super();
		authenticationRequired = ["cms_admin", "cms_manager"];
	}
	
	override public function main():Void
	{	
		action = app.params.get("action");
		actionId = Std.parseInt(app.params.get("id"));
		
		if (action != "edit" && action != "add") action = "add";
		heading = (action == "edit") ? "Edit User" : "Add User";
		
		setupForm1();
		if (form1.isSubmitted() && form1.isValid())
			process();
		super.main();
	}
	
	private function process():Void
	{
		switch(action)
		{
			case "add":
				var d:Dynamic = form1.getData();
				var exists = app.db.exists("_users", "`username`=\""+d.username+"\"");
				
				if(!exists){
					try {	
						var s = Web.getParamValues(form1.name + "_groups");
						var a = (s != null) ? s.join(",") : "";
						d.groups = a;
						d.password = Md5.encode(d.password);
						d.added = Date.now();
						app.db.insert("_users", d);
					}catch (e:Dynamic) {
						//if (app.debug) throw(e);
						messages.addError("Database error.");
					}
					if (app.db.lastAffectedRows < 1) {
						messages.addError("Problem adding user.");
					}else {
						messages.addMessage("User added. <a href=\"?request=cms.modules.base.User&action=edit&id=" + app.db.cnx.lastInsertId() + "\">edit</a>");
						form1.clearData();
					}
				} else {
					messages.addError("User '"+d.username+"' aready exists");
				}
			case "edit":
				var d:Dynamic = form1.getData();
				
				try {	
					var s = Web.getParamValues(form1.name + "_groups");
					var a = (s != null) ? s.join(",") : "";
					d.groups = a;
					var oldPassword = app.db.requestSingle("SELECT password FROM `_users` WHERE id=" + actionId).password;
					if (d.password != oldPassword)
						d.password = Md5.encode(d.password);
					
					app.db.update("_users", d, "id="+form1.getElement("actionId").value);
				}catch (e:Dynamic) {
					if (app.db.exists("_users", "`username`=\"" + d.username + "\""))
					{
						messages.addError("Another user '"+d.username+"' already exists");
					} else {
						messages.addError("Database error.");
					}
				}
				if (app.db.lastAffectedRows < 1) {
					messages.addWarning("Nothing changed.");
				}else {
					messages.addMessage("User updated.");
				}
		}
	}
	
	private function setupForm1():Void
	{
		var userInfo:Dynamic = { };
		var groupsSelected:Array<String> = new Array();
		
		var groups:List<Dynamic>;
		var sql:String;
		
		if (!user.isSuper()) {
			sql = "SELECT `stub` AS 'key', `name` AS 'value' FROM _users_groups WHERE isAdmin=0 AND isSuper=0";
			groups = app.db.request(sql);
		}else {
			// super, they can do anything!
			sql = "SELECT `stub` AS 'key', `name` AS 'value' FROM _users_groups";
			groups = app.db.request(sql);
		}
		
		if (action == "edit") {
			userInfo = app.db.requestSingle("SELECT * FROM `_users` WHERE `id`=" + actionId);
			groupsSelected = userInfo.groups.split(",");
		}
		
		form1 = new Form("form1");
		form1.addElement(new Hidden("actionId", actionId));
		form1.addElement(new Input("username", "Username", userInfo.username, true));
		var pass = new Input("password", "Password", userInfo.password, true);
		pass.password = true;
		form1.addElement(pass);
		
		form1.addElement(new Input("name", "Name", userInfo.name, true));
		form1.addElement(new Input("email", "Email", userInfo.email, true));
		form1.addElement(new CheckboxGroup("groups", "Group", groups, groupsSelected));
		form1.addElement(new Button("submit", "Update", (action == "edit") ? "Update" : "Add"));
		
		form1.populateElements();
	}
}