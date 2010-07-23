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

import poko.views.renderers.Templo;
import php.Web;
import site.cms.common.Tools;
import site.cms.templates.CmsTemplate;


class Definitions extends DefinitionsBase
{
	
	public var assigned:List<Dynamic>;
	public var unassigned:List<Dynamic>;
	
	public var pageLabel:String;
	
	public function new() 
	{
		super();
		authenticationRequired = ["cms_admin", "cms_manager"];
	}
	
	override public function main()
	{
		super.main();
		
		process();
		
		pageLabel = pagesMode ? "Page" : "Dataset";
		
		if (app.params.get("manage") == null)
		{			
			var str = "< Select a Dataset";
			
			str += Templo.parse("site/cms/modules/base/blocks/definitions.mtt");
			
			setContentOutput(str);
		}
		
		var tables = Tools.getDBTables();
		var defs = app.db.request("SELECT * FROM `_definitions` WHERE `isPage`='" + pagesMode +"' ORDER BY `order`");
		
		unassigned = new List();
		assigned = new List();
		for (def in defs)
		{
			tables.remove(def.table);
			assigned.add(def);
		}
		
		for(table in tables)
			unassigned.add( { name:table } );
			
		setupLeftNav();
	}
	
	private function process():Void
	{
		switch(app.params.get("action"))
		{
			case "add":
			
				var nextId = app.db.requestSingle("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" + pagesMode +"'").order;
				nextId++;
				
				app.db.insert("_definitions", { name:app.params.get("name"), isPage:pagesMode, order:nextId } );
				
				var defId = app.db.cnx.lastInsertId();
				
				if (pagesMode)
					app.db.insert("_pages", { name:app.params.get("name"), definitionId:defId } );
				
			case "update":
				var deleteList = Web.getParamValues("delete");
				if (deleteList != null)
				{
					for (i in 0...deleteList.length)
					{
						if (deleteList[i] != null) 
						{
							var defId = app.db.cnx.quote(Std.string(i));
							app.db.delete("_definitions", "`id`=" + defId);
							app.db.delete("_pages", "`definitionId`=" + defId);
						}
					}
				}
				
				var c = 0;
				for (val in php.Web.getParamValues("order"))
				{
					if (val != null) app.db.update("_definitions", { order:val }, "`id`=" + c);
					c++;
				}
				
				//RESORT ORD
				c = 0;
				var result = app.db.request("SELECT `id` as 'id' from `_definitions` WHERE `isPage`='"+pagesMode+"' ORDER BY `order`");
				for (item in result)
					app.db.update("_definitions", {order:++c}, "`id`='" + item.id + "'");
				
			case "define":
			
				var nextId = app.db.requestSingle("SELECT MAX(`order`) as 'order' FROM `_definitions` WHERE `isPage`='" + pagesMode +"'").order;
				nextId++;
				var name = app.params.get("define");
				app.db.insert("_definitions", { name:name, table:name, isPage:false, order:nextId } );
		}
		
	}

}



class DefinitionsBase extends CmsTemplate
{
	private var pagesMode:Bool;
	
	override public function init()
	{
		super.init();
		
		pagesMode = app.params.get("pagesMode") == "true";
		
		if (pagesMode) navigation.setSelected("Pages"); else navigation.setSelected("Dataset");
	}
	
	override public function main()
	{
		refreshDefinitions();
	}
	
	public function refreshDefinitions()
	{
		
	}
	
	private function setupLeftNav():Void
	{
		refreshDefinitions();
		
		var isAdmin = user.isAdmin();
		
		if(pagesMode){
			leftNavigation.footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true&pagesMode=true\">Manage Pages</a>";
			leftNavigation.addSection("Pages");
			
			var pages = app.db.request("SELECT *, p.id as pid FROM `_pages` p, `_definitions` d WHERE p.definitionId=d.id ORDER BY d.`order`");
			
			leftNavigation.addSection("Pages");
			
			for (page in pages)
			{
				//leftNavigation.addLink("Pages", page.name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" + page.pid, page.indents);	
				if ( isAdmin )
					leftNavigation.addLink("Pages", page.name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" + page.pid, page.indents, false, 
						leftNavigation.editTag( "cms.modules.base.Definition&id=" + page.id + "&pagesMode=true" ) );
				else
					leftNavigation.addLink("Pages", page.name, "cms.modules.base.DatasetItem&pagesMode=true&action=edit&id=" + page.pid, page.indents);	
			}
		}
		else 
		{
			var tables:List <Dynamic> = app.db.request("SELECT * FROM `_definitions` d WHERE d.isPage='0' ORDER BY `order`");
			
			// build the nav
			leftNavigation.addSection("Datasets");			
			
			var def:Dynamic = Definition;
			for (table in tables)
			{
				if(table.showInMenu){
					var name = table.name != "" ? table.name : table.table;
					//leftNavigation.addLink("Datasets", name, "cms.modules.base.Dataset&dataset=" + table.id, table.indents);
					if ( isAdmin )
						leftNavigation.addLink("Datasets", name, "cms.modules.base.Dataset&dataset=" + table.id, table.indents, false, 
							leftNavigation.editTag( "cms.modules.base.Definition&id=" + table.id + "&pagesMode=false" ) );
					else
						leftNavigation.addLink("Datasets", name, "cms.modules.base.Dataset&dataset=" + table.id, table.indents);
				}
			}
			
			if (user.isAdmin() || user.isSuper())
				leftNavigation.footer = "<a href=\"?request=cms.modules.base.Definitions&manage=true\">Manage</a>";
		}
	}
}
