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

package site.cms.components;

import haxe.Stack;
import poko.system.Component;
import php.Web;
import site.cms.CmsController;

class Navigation extends Component
{
	public var pageHeading:String;
	public var content:String;
	private var selected:String;
	public var userName:String;
	
	public var items:Hash<String>;
	
	override public function init()
	{
		var name:String = app.params.get("request");
		name = name.substr(name.lastIndexOf(".") + 1);
		
		pageHeading = "page";
		
		//setSelected(name);
		
		var cmsController:CmsController = cast app.controller;
		items = new Hash();
		
		if(cmsController.user.authenticated){

			if (cmsController.user.isSuper()) {
				items.set("modules.base.Pages", "Pages");
				items.set("modules.base.Datasets", "Data");
				items.set("modules.base.SiteView", "Site Map");
				items.set("modules.media.Index", "Media");
				items.set("modules.base.Settings", "Settings");
				items.set("modules.base.Users", "Users");
				items.set("modules.base.DbBackup", "Backup");
				items.set("modules.help.Help", "Help");
			}else if (cmsController.user.isAdmin()) {
				items.set("modules.base.SiteView", "Site Map");
				items.set("modules.base.Users", "Users");
				items.set("modules.base.DbBackup", "Backup");
			}else{
				items.set("modules.base.SiteView", "Site Map");
			}

			items.set("modules.email.Index", "Email");
			items.set("modules.base.ChangePassword", "Password");
		}
	}
	
	override public function main() 
	{
		var requests = new Hash();
		//requests.set("Home", "Home");
		
		var cmsController:CmsController = cast app.controller;
		
		if(cmsController.user.authenticated){
			content = "<ul id=\"headingNavigation\">\n";
			
			for (k in app.config.navigationExt.keys())
				items.set(k, app.config.navigationExt.get(k));
			
			for (item in items.keys())
			{
				var parts = item.split(".");
				if (parts[parts.length-1] == selected)
				{
					content += "<li><a href=\"?request=cms."+item+"\" class=\"navigation_selected\">" + items.get(item) + "</a></li>\n";
				} else {
					content += "<li><a href=\"?request=cms."+item+"\">"+items.get(item)+"</a></li>\n";
				}
			}
			
			content += "</ul>\n";
			
			userName = cmsController.user.name;
		}else {
			content = null;
		}
	}
	
	public function setSelected(id:String)
	{
		selected = id;
	}
	
}
