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
import php.Lib;
import poko.Poko;
import poko.views.renderers.Templo;
import poko.js.JsBinding;
import site.cms.modules.base.helper.MenuDef;
import site.cms.templates.CmsTemplate;
import site.cms.modules.base.Datasets;

class SiteView extends DatasetBase
{
	var jsBind:JsBinding;	
	
	public function new() 
	{
		super();
	}
	
	override public function init()
	{
		super.init();
			
		head.js.add("js/cms/jquery-ui-1.7.2.custom.min.js");
		head.css.add("css/cms/ui-lightness/jquery-ui-1.7.2.custom.css");
		
		siteMode = true;
		
		navigation.setSelected("SiteView");
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsSiteView");
	}
	
	override public function main()
	{
		super.main();
		
		if (!app.params.get("manage") || !user.isAdmin())
		{
			var str = "";
			
			if (user.isAdmin() || user.isSuper())
				str += Templo.parse("cms/modules/base/blocks/siteView.mtt");
				
			setContentOutput(str);
		}else {
			if (app.params.get('action') == "saveSiteView") {
				var d = app.params.get('siteViewData');
				
				app.db.update('_settings', {value: d}, "`key` ='siteView'");
				messages.addMessage("Menu updated.");
			}
		}
		
		setupLeftNav();
		return;
		
		// Managment is done in the definitions page
	}
}