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

import poko.Component;
import php.Web;

class Navigation extends Component
{
	public var pageHeading:String;
	public var content:String;
	private var selected:String;
	
	public function new() 
	{
		super();
		
		var name:String = application.params.get("request");
		name = name.substr(name.lastIndexOf(".") + 1);
		
		pageHeading = "page";
		
		setSelected(name);
	}
	
	override public function main() 
	{
		var requests = new Hash();
		requests.set("Home", "Home");
		
		if (application.user.isAdmin() || application.user.isSuper()) {
			requests.set("modules.base.Pages", "Pages");
			requests.set("modules.base.Datasets", "Lists");			
			requests.set("modules.base.SiteView", "Site View");
			requests.set("modules.media.Index", "Media");
		}else {
			requests.set("modules.base.SiteView", "Site Editor");
		}
		
		requests.set("modules.help.Help", "Help");
		
		//if (application.user.isAdmin() || application.user.isSuper()) {
			requests.set("modules.base.Users", "Users");
		//}
		
		content = "<ul>\n";

		for (request in requests.keys())
		{
			
			if (request == selected)
			{
				content += "<li>" + requests.get(request) + "</li>\n";
			} else {
				content += "<li><a href=\"?request=cms."+request+"\">"+requests.get(request)+"</a></li>\n";
			}
		}
		
		content += "<li><a href=\"?request=cms.Index&logout=true\">logout</a></li>\n";
		
		content += "</ul>\n";
	}
	
	public function setSelected(id:String)
	{
		selected = id;
	}
	
}
