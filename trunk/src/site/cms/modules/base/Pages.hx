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

package site.cms.modules.base;
import poko.Application;
import poko.TemploContext;
import poko.js.JsBinding;
import site.cms.templates.CmsTemplate;


class Pages extends PageBase
{
	
	public function new() 
	{
		super();
	}
	
	override public function pre()
	{		
	}
	
	
	override public function main()
	{
		super.main();
		
		if (!application.params.get("manage"))
		{
			var str = "< Select a Page";
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				str += TemploContext.parse("site/cms/modules/base/blocks/pages.mtt");
				
			setContentOutput(str);
			
			setupLeftNav();
			return;
		}	
		
		// Managment is done in the definitions page
	}
}


class PageBase extends CmsTemplate
{
	public var pages:List<Dynamic>;
	
	override public function pre()
	{
		navigation.setSelected("Pages");
	}
	
	public function setupLeftNav():Void
	{	
		super.main();
		
		pages = application.db.request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`" );
		
		leftNavigation.addSection("Pages");
		
		for (page in pages)
		{
			leftNavigation.addLink("Pages", page.name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id="+page.pid, page.indents);
		}
		
		if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
			leftNavigation.footer = "<br /><a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a><br />";
	}
	
}
