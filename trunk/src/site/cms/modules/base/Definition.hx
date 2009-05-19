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

import poko.form.elements.RadioGroup;
import poko.form.elements.Readonly;
import poko.utils.JsBinding;
import site.cms.common.Definition;
import site.cms.common.DefinitionElementMeta;
import poko.form.elements.Button;
import poko.form.elements.Input;
import poko.form.elements.Selectbox;
import poko.utils.PhpTools;
import php.Web;
import site.cms.modules.base.Definitions;
import poko.form.Form;
import site.cms.components.DbStructureSelector;

class Definition extends DefinitionsBase
{	
	public var form1:Form;
	public var id:Int;
	public var elements:List<Dynamic>;
	public var undefinedFields:List<Dynamic>;
	
	public var definition:site.cms.common.Definition;
	
	public var jsBind:JsBinding;
	
	override public function pre()
	{
		super.pre();
		
		id = application.params.get("id");
		definition = new site.cms.common.Definition(id);
		
		//
		
		remoting.addObject("api", { toggleCheckbox:toggleCheckbox } );
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsDefinition");
	}
	
	
	public function toggleCheckbox(field:String, index:Int, type:String)
	{
		var element = definition.getElement(field);
		var val = Reflect.setField(element, type, !Reflect.field(element, type));
		definition.save();
		
		var d:Dynamic = { };
		d.field = field;
		d.index = index;
		d.type = type;
		d.value = val;
		return d;
	}
	
	override public function main()
	{
		super.main();
		
		setupForm1();
		
		// Update data
		if (form1.isSubmitted())
		{
			application.db.update("_definitions", form1.getData(), "`id`=" + id);
		}
		
			
	
		if (application.params.get("action"))
			process();
			
		setupForm1();
		
		elements = new List();
		
		if (pagesMode)
		{
			for (el in definition.elements)
				elements.add(el);
		} 
		else 
		{	
			var fields = application.db.request("SHOW FIELDS FROM `" + definition.table + "`");
			var defined = new List();
			for (field in fields)
			{
				var def = definition.getElement(field.Field);
				if (def != null)
				{
					def.dbtype = field.Type;
					elements.add(def);
					defined.add(def.name);
				}
			}
			
			fields = Lambda.filter(fields, function(field)
			{
				return !Lambda.has(defined, field.Field);
			});
			
			undefinedFields = fields;
		}
		
		this.setupLeftNav();
	}
	
	private function process():Void
	{
		switch(application.params.get("action"))
		{
			// Used in pagesMode
			case "addElement":
				var name = application.params.get("elementName");
				var type = "hidden";
				
				if (name == "") {
					
					trace("name missing");
					return;
				}
				
				var el = definition.addElement(name);
				if (el != null) 
				{
					el.type = "read-only";
					el.showInList = true;
					definition.save();
				} else {
					trace("name missing");
				}
			case "update":
				
				// Delete
				if(Web.getParamValues("delete") != null){
					for (el in Web.getParamValues("delete"))
					{
						definition.removeElement(el);
					}
				}
				definition.save();
				
				//Order
				definition.reOrderElements(Web.getParamValues("order"));
			
			// Used in !pagesMode	
			case "define":
				var define = application.params.get("define");
				var el = definition.addElement(define);
				el.type = "read-only";
				el.showInList = true;
				definition.save();
				application.redirect("?request=cms.modules.base.DefinitionElement&id=" + id + "&definition=" + define + "&pagesMode=false");
			case "addExtra":
				switch(application.params.get("extra"))
				{
					case "linkdisplay":
						var lastlink = null; 
						for (el in definition.elements)
							if (el.type == "linkdisplay")
								lastlink = el;
						var linkname = "link_";
						if(lastlink == null)
							linkname += "1";
						else 
							linkname += cast Std.parseInt(cast(lastlink.name.split("_")[1])) + 1;
							
						var el = definition.addElement(linkname);
						el.type = "linkdisplay";
						definition.save();
					case "multilink":
						var lastlink = null; 
						for (el in definition.elements)
							if (el.type == "multilink")
								lastlink = el;
						var linkname = "multilink_";
						if(lastlink == null)
							linkname += "1";
						else 
							linkname += cast Std.parseInt(cast(lastlink.name.split("_")[1])) + 1;
							
						var el = definition.addElement(linkname);
						el.type = "multilink";
						definition.save();
				}
				
		}
	}
	
	private function setupForm1():Void
	{
		var generalInfo = application.db.requestSingle("SELECT * FROM `_definitions` WHERE `id`=" + id);
		
		// for bool selectors
		var yesno = new List();
		yesno.add( { key:"Yes", value:"1" } );
		yesno.add( { key:"No", value:"0" } );
		
		form1 = new Form("form1");
		if(!pagesMode) form1.addElement(new Readonly("table", "Table", generalInfo.table));
		form1.addElement(new Input("name", "Name", generalInfo.name, false));
		form1.addElement(new Input("description", "Description", generalInfo.description, false));
		form1.addElement(new RadioGroup("showFiltering", "Filtering?", yesno, generalInfo.showFiltering, "0", false));
		form1.addElement(new RadioGroup("showOrdering", "Ordering?", yesno, generalInfo.showOrdering, "0", false));
		form1.addElement(new RadioGroup("showInMenu", "In Menu?", yesno, generalInfo.showInMenu, "0", false));
		form1.addElement(new Selectbox("indents", "Indents", null, generalInfo.indents, false, "- none -" ));
		form1.addElement(new Button("submit", "Update", "Update"));
		form1.submitButton = form1.getElement("submit");
		form1.populateElements();
		
		
		var indentSelector = form1.getElementTyped("indents", Selectbox);
		indentSelector.addOption( { key:1, value:1 } );
		indentSelector.addOption( { key:2, value:2 } );
		indentSelector.addOption( { key:3, value:3 } );
		indentSelector.addOption( { key:4, value:4 } );
		
	}
	
}