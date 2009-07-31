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

import haxe.Serializer;
import haxe.Unserializer;
import php.Exception;
import poko.Application;
import poko.form.elements.Button;
import poko.form.elements.Input;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.form.FormElement;
import poko.Request;
import poko.TemploContext;
import php.Web;
import site.cms.common.Tools;
import site.cms.modules.base.helper.MenuDef;
import site.cms.templates.CmsTemplate;

class Datasets extends DatasetBase
{
	public var data:List<Dynamic>;
	
	public function new() 
	{
		super();
	}
	
	override public function main()
	{
		if (application.params.get("manage") == null)
		{
			var str = "< Select a Dataset";
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				str += TemploContext.parse("site/cms/modules/base/blocks/datasets.mtt",{});
			
			setContentOutput(str);
			
			setupLeftNav();
			return;
		}
		
		// Managment is done in the definitions page
	}
}

class DatasetBase extends CmsTemplate
{
	public var pagesMode:Bool;
	public var siteMode:Bool;
	public var linkMode:Bool;
	
	public var siteView:MenuDef;
	public var siteViewHidden:MenuDef;
	
	public var siteViewSerialized:String;
	public var siteViewHiddenSerialized:String;
	
	override public function pre()
	{
		if (application.params.get("manage") != null) {
			authenticationRequired = ["cms_admin", "cms_manager"];
		}
		
		linkMode = application.params.get("linkMode") == "true";
		pagesMode = application.params.get("pagesMode") == "true";
		siteMode = application.params.get("siteMode") == "true" || siteMode;
		
		if (pagesMode) 
		{
			navigation.pageHeading = "Pages";
			navigation.setSelected("Pages");
		} else if (siteMode) {
			navigation.pageHeading = "Site"; 
			navigation.setSelected("SiteView");				
		}else{
			navigation.pageHeading = "Datasets"; 
			navigation.setSelected("Datasets");
		}
	}
	
	private function setupLeftNav():Void
	{
		if (pagesMode && !siteMode) 
		{
			var pages = application.db.request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			
			leftNavigation.addSection("Pages");

			for (page in pages) {
				leftNavigation.addLink("Pages", page.name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" + page.pid, page.indents);
			}
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				leftNavigation.footer = "<br /><a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a><br />";
				
		} else if (siteMode) { 
			
			var pages = application.db.request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			var tables = application.db.request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
			
			var siteViewData:String = application.db.requestSingle("SELECT `value` FROM `_settings` WHERE `key`='siteView'").value;
			var menu:MenuDef = new MenuDef();
	
			siteView = new MenuDef();
			
			try {
				menu = Unserializer.run(siteViewData);
			}catch (e:Dynamic) {
			}

			if (menu.headings.length == 0) {
				leftNavigation.addSection("ERROR");
				leftNavigation.addLink("ERROR", ">> Manage", "cms.modules.base.SiteView&manage=true");
			}else {
				for (heading in menu.headings) {
					leftNavigation.addSection(heading.name, heading.isSeperator);
					if(!heading.isSeperator){
						siteView.addHeading(heading.name);
					}else {
						siteView.addSeperator();
					}
				}

				// go through menu items and make sure they still exist in DB, otherwise just ignore them and they'll be removed on the next save!
				for (item in menu.items) {
					switch(item.type) {
						case MenuItemType.DATASET:
							if(Lambda.exists(tables, function(x){
								return(x.id == item.id);
							})) {
								// add to nav
								var link = "cms.modules.base.Dataset&dataset=" + item.id + "&resetState=true&siteMode=true";
								leftNavigation.addLink(item.heading, item.name, link, item.indent);
								
								// remove from tables list
								tables = Lambda.filter(tables, function(x)
								{
									return x.id != item.id;
								});
								
								// add to printing list
								siteView.addItem(item.id, item.type, item.name, item.heading, item.indent);
							}
						case MenuItemType.PAGE:
							if(Lambda.exists(pages, function(x){
								return(x.id == item.id);
							})) {
								// add to nav
								var link = "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id="+item.id+"&siteMode=true";
								leftNavigation.addLink(item.heading, item.name, link, item.indent);
								
								// remove from pages list
								pages = Lambda.filter(pages, function(x)
								{
									return x.id != item.id;
								});
								
								// add to printing list
								siteView.addItem(item.id, item.type, item.name, item.heading, item.indent);
							}
						case MenuItemType.NULL:
							// add to nav
							leftNavigation.addLink(item.heading, item.name, null, item.indent);
							
							// add to printing list
							siteView.addItem(item.id, item.type, item.name, item.heading, item.indent);
					}
				}
			}
			
			// add the non-shown stuff
			siteViewHidden = new MenuDef();
			for (item in tables) {
				siteViewHidden.addItem(item.id, MenuItemType.DATASET, item.name);
			}
			for (item in pages) {
				siteViewHidden.addItem(item.id, MenuItemType.PAGE, item.name);
			}
			
			siteViewSerialized = Serializer.run(siteView);
			siteViewHiddenSerialized = Serializer.run(siteViewHidden);

			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				leftNavigation.footer = "<br /><a href=\"?request=cms.modules.base.SiteView&manage=true\">Manage Menu</a><br />";
			
		}else{
			var tables:List <Dynamic> = application.db.request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
			
			// build the nav
			leftNavigation.addSection("Datasets");
			
			var def:Dynamic = Definition;
			for (table in tables)
			{
				if(table.showInMenu){
					var name = table.name != "" ? table.name : table.table;
					leftNavigation.addLink("Datasets", name, "cms.modules.base.Dataset&dataset=" + table.id + "&resetState=true", table.indents);
				}
			}
			
			if (Application.instance.user.isAdmin() || Application.instance.user.isSuper())
				leftNavigation.footer = "<br /><a href=\"?request=cms.modules.base.Definitions&manage=true\">Manage Lists</a><br />";			
		}
	}
}