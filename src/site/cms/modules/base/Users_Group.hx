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
import poko.form.Form;
import poko.utils.PhpTools;
import php.Web;
import site.cms.modules.base.Users;

class Users_Group extends UsersBase
{
	public var userData:List<Dynamic>;
	public var action:String;
	public var actionId:Int;
	
	public var form1:Form;
	public var heading:String;
	
	public function new() 
	{
		authenticationRequired = ["cms_admin"];
		super();
	}
	
	override public function main():Void
	{	
		action = app.params.get("action");
		actionId = Std.parseInt(app.params.get("id"));
		
		if (action != "edit" && action != "add") action = "add";
		heading = (action == "edit") ? "Edit Group" : "Add Group";
		
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
				try {	
					var d:Dynamic = form1.getData();
					var a = Web.getParamValues(form1.name + "_permissions");
					d.isAdmin = '0';
					d.isSuper = '0';
					if (a != null) {
						for(s in a)
							Reflect.setField(d, s, '1');
					}
					app.db.insert("_users_groups", d);
				}catch (e:Dynamic) {
					messages.addError("Database error.");
				}
				if (app.db.lastAffectedRows < 1) {
					messages.addError("Problem adding group.");
				}else {
					messages.addMessage("Group added. <a href=\"?request=cms.modules.base.Users_Group&action=edit&id="+app.db.cnx.lastInsertId()+"\">edit</a>");
					form1.clearData();
				}
			case "edit":
				try {
					var d:Dynamic = form1.getData();
					var a = Web.getParamValues(form1.name + "_permissions");
					d.isAdmin = '0';
					d.isSuper = '0';
					if (a != null) {
						for(s in a)
							Reflect.setField(d, s, '1');
					}
					app.db.update("_users_groups", d, "id=" + form1.getElement("actionId").value);
				}catch (e:Dynamic) {
					messages.addError("Database error.");
				}
				if (app.db.lastAffectedRows < 1) {
					messages.addWarning("Nothing changed.");
				}else {
					messages.addMessage("Group updated.");
				}
		}
	}
	
	private function setupForm1():Void
	{
		var groupInfo:Dynamic = { };
		var permissionsListSelected:Array<String> = new Array();
		
		if (action == "edit") {
			groupInfo = app.db.requestSingle("SELECT * FROM `_users_groups` WHERE `id`=" + actionId);
			if (groupInfo.isAdmin) permissionsListSelected.push('isAdmin');
			if (groupInfo.isSuper) permissionsListSelected.push('isSuper');
		}
		
		var permissionsList:List<Dynamic> = new List();
		permissionsList.add( { key:'isAdmin', value:"Admin" } );
		permissionsList.add( { key:'isSuper', value:"Super" } );
		
		form1 = new Form("form1");
		form1.addElement(new Hidden("actionId", actionId));
		form1.addElement(new Input("stub", "Stub", groupInfo.stub, true));
		form1.addElement(new Input("name", "Name", groupInfo.name, true));
		form1.addElement(new Input("description", "Description", groupInfo.description, true));
		form1.addElement(new CheckboxGroup("permissions", "Permissions", permissionsList, permissionsListSelected));
		form1.addElement(new Button("submit", "Update", (action == "edit") ? "Update" : "Add"));
		form1.populateElements();

		form1.setSubmitButton(form1.getElement("submit") );
	}	
}