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

import php.Web;
import site.cms.templates.CmsTemplate;

class Users extends UsersBase
{
	public var users:List<Dynamic>;	
	public var action:String;
	public var actionId:Int;
	
	public function new() 
	{
		super();
		authenticationRequired = ["cms_admin", "cms_manager"];
	}
	
	override public function main():Void
	{			
		super.main();
		
		action = app.params.get("action");
		actionId = Std.parseInt(app.params.get("id"));
		
		if (action == "delete") {
			if (actionId == null) {
				messages.addError("Trying to delete, no ID given.");
			}else {
				try{
					app.db.delete("_users", "id=" + actionId);
				}catch (e:Dynamic) {
					messages.addError("Can't delete user.");
				}
				trace(app.db.lastAffectedRows);
				if (app.db.lastAffectedRows > 0) {
					messages.addMessage("User deleted.");
				}else {
					messages.addWarning("No user to delete.");
				}
			}
		}
		
		view.template = "cms/modules/base/Users.mtt";
		if(user.isSuper()){
			users = app.db.request("SELECT * FROM _users");
		}else {
			users = app.db.request("SELECT * FROM _users WHERE groups NOT LIKE '%cms_admin%'");
		}
	}
}

class UsersBase extends CmsTemplate
{
	override public function main()
	{
		super.main();		
		
		navigation.setSelected("Users");
		
		setupLeftNav();
	}
	
	private function setupLeftNav():Void
	{
		// build the nav
		leftNavigation.addSection("Users");
		leftNavigation.addLink("Users", "List Users", "cms.modules.base.Users&action=list");
		leftNavigation.addLink("Users", "Add User", "cms.modules.base.User&action=add");
		
		if(user.isSuper()){
			leftNavigation.addSection("Groups");
			leftNavigation.addLink("Groups", "List Groups", "cms.modules.base.Users_Groups&action=list");
			leftNavigation.addLink("Groups", "Add Group", "cms.modules.base.Users_Group&action=add");
		}
	}
}
