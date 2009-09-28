/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tarwin Stroh-Spijer <tarwin@touchmypixel.com>
 * Contributers: Tony Polinelli <tonyp@touchmypixel.com>
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
package site.cms.modules.base.js;
import haxe.Serializer;
import haxe.Unserializer;
import js.Dom;
import js.Lib;
import js.XMLHttpRequest;
import poko.js.JsRequest;
import site.cms.modules.base.helper.MenuDef;
import poko.js.JsUtils;

class JsSiteView extends JsRequest
{
	override public function main()
	{				
		var _t = this;
		new JQuery(Lib.document).ready(function()
		{
			createSorter();
			
			_t.refreshBehaviour();
			
			// setup adders
			new JQuery("#addSeperatorButton").click(_t.addSeperator);
			new JQuery("#addSectionButton").click(_t.addSection);
			new JQuery("#addNullButton").click(_t.addNull);
			
			_t.flushSorter(null);
		});		
	}
	
	private function addSection(e:Event):Void
	{
		var j = new JQuery("#addSectionInput");
		if (!j.val()) {
			Lib.alert("Please enter a name.");
			j[0].focus();
		}else {
			var s = "<li class=\"sectionHeading\"><p><span>" + j.val() + "</span> <a href=\"#\" class=\"editItem\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></p><ul class=\"connectedSortable\"></ul></li>";
			var j = new JQuery("#siteViewSection");
			j.html(s + j.html());
			j.val("");
			refreshBehaviour();
			flushSorter(null);
		}
		untyped e.preventDefault();
	}
	
	private function addSeperator(e:Event):Void
	{
		var s = "<li class=\"sectionSeperator\">seperator <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></li>";
		var j = new JQuery("#siteViewSection");
		j.html(s + j.html());
		
		refreshBehaviour();
		flushSorter(null);
		untyped e.preventDefault();
	}
	
	private function addNull(e:Event):Void
	{
		var j = new JQuery("#addNullInput");
		if (!j.val()) {
			Lib.alert("Please enter a name.");
			j[0].focus();
		}else {
			var s = "<li><img src=\"./res/cms/site_list_null.png\" align=\"absmiddle\" /> <span class=\"listTreeIndent0\" data=\""+ Serializer.run({id:0, type:MenuItemType.NULL}) +"\" >";
			s += j.val() + "</span> <a href=\"#\" class=\"editItemInner\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <div class=\"connectedSortableMover\"><a href=\"#\">-</a> <a href=\"#\">+</a></div></li>";
			var j = new JQuery("#siteViewHiddenSection");
			j.html(s + j.html());
			
			refreshBehaviour();
			flushSorter(null);
		}
		untyped e.preventDefault();
	}	
	
	public function refreshBehaviour():Void
	{
		// setup sorters
		var j:JQuery;
		j = new JQuery("#siteViewSection"); j.sortable( {
			axis: 'y',
			opacity: 0.8,
			update: flushSorter
		}).disableSelection();
		j = new JQuery(".connectedSortable"); j.sortable( {
			connectWith: '.connectedSortable',
			axis: 'y',
			opacity: 0.8,
			update: flushSorter
		}).disableSelection();		
		
		// minus
		new JQuery(".connectedSortableMover a:even").unbind("click", minus);
		new JQuery(".connectedSortableMover a:even").bind("click", null, minus);
		// plus
		new JQuery(".connectedSortableMover a:odd").unbind("click", plus);
		new JQuery(".connectedSortableMover a:odd").bind("click", null, plus);
		
		// remove seperator
		new JQuery(".sectionSeperator a.deleteItem").unbind("click", removeSeperator);
		new JQuery(".sectionSeperator a.deleteItem").bind("click", null, removeSeperator);
		// remove heading
		new JQuery(".sectionHeading a.deleteItem").unbind("click", removeHeading);
		new JQuery(".sectionHeading a.deleteItem").bind("click", null, removeHeading);
		
		// edit heading
		new JQuery(".sectionHeading a.editItem").unbind("click", editHeading);
		new JQuery(".sectionHeading a.editItem").bind("click", null, editHeading);
		
		// edit item
		new JQuery("a.editItemInner").unbind("click", editItem);
		new JQuery("a.editItemInner").bind("click", null, editItem);
	}
	
	static function createSorter()
	{
		// get data
		var j = new JQuery("#siteView");
		var m:MenuDef = new MenuDef();
		try {
			m = Unserializer.run(j.val());
		}catch (e:Dynamic) {}
				
		// create lists of added data
		var s = "";
		for (section in m.headings)
		{
			if (section.isSeperator) {
				s += "<li class=\"sectionSeperator\">seperator <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></li>";
			}else{
				var name = section.name;
				s += "<li class=\"sectionHeading\"><p><span>" + section.name + "</span> <a href=\"#\" class=\"editItem\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></p>"; // start section
				s += "<ul class=\"connectedSortable\">"; // start list of items
				for (item in m.items) {
					if (item.heading == name) {
						s += "<li>";
						s += "<img src=\"./res/cms/";
						s += switch(item.type) {
							case MenuItemType.DATASET: "site_list_list.png";
							case MenuItemType.PAGE: "site_list_page.png";
							case MenuItemType.NULL: "site_list_null.png";
						}
						s += "\" align=\"absmiddle\" /> <span class=\"listTreeIndent" + item.indent + "\" data=\"" + Serializer.run( { id:item.id, type:item.type } ) + "\">";
						s += item.name + "</span> <a href=\"#\" class=\"editItemInner\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <div class=\"connectedSortableMover\"><a href=\"#\">-</a> <a href=\"#\">+</a></div></li>";
					}
				}
				s += "</ul>"; //end list of items
				s += "</li>"; // end section
			}
		}
		// add
		j = new JQuery("#siteViewSection");
		j.html(s);
		
		// create lists of hidden data
		j = new JQuery("#siteViewHidden");
		try {
			m = Unserializer.run(j.val());
		}catch (e:Dynamic) {}
		
		s = "";
		for (item in m.items) {
			s += "<li>";
			s += "<img src=\"./res/cms/";
			s += switch(item.type) {
				case MenuItemType.DATASET: "site_list_list.png";
				case MenuItemType.PAGE: "site_list_page.png";
				case MenuItemType.NULL: "site_list_null.png";
			}
			s += "\" align=\"absmiddle\" /> ";
			s += "<span data=\"" + Serializer.run( { id:item.id, type:item.type } ) + "\">" + item.name + "</span><div class=\"connectedSortableMover\"><a href=\"#\">-</a> <a href=\"#\">+</a></div></li>";
		}
		
		// add
		j = new JQuery("#siteViewHiddenSection");
		j.html(s);
	}
	
	function minus(e:Event){
		var t = new JQuery(untyped e.currentTarget).parent().parent().find("span");
		var cC = 0;
		if (t.hasClass("listTreeIndent1")) cC = 1;
		if (t.hasClass("listTreeIndent2")) cC = 2;
		if (t.hasClass("listTreeIndent3")) cC = 3;
		if (t.hasClass("listTreeIndent4")) cC = 4;
		t.removeClass("listTreeIndent1");
		t.removeClass("listTreeIndent2");
		t.removeClass("listTreeIndent3");
		t.removeClass("listTreeIndent4");
		switch(cC){
			case 2: t.addClass("listTreeIndent1");
			case 3: t.addClass("listTreeIndent2");
			case 4: t.addClass("listTreeIndent3");
		}
		
		flushSorter(null);
		untyped e.preventDefault();
	}
	
	function plus(e:Event){
		var t = new JQuery(untyped e.currentTarget).parent().parent().find("span");
		var cC = 0;
		if (t.hasClass("listTreeIndent1")) cC = 1;
		if (t.hasClass("listTreeIndent2")) cC = 2;
		if (t.hasClass("listTreeIndent3")) cC = 3;
		if (t.hasClass("listTreeIndent4")) cC = 4;
		t.removeClass("listTreeIndent1");
		t.removeClass("listTreeIndent2");
		t.removeClass("listTreeIndent3");
		t.removeClass("listTreeIndent4");
		switch(cC){
			case 0: t.addClass("listTreeIndent1");
			case 1: t.addClass("listTreeIndent2");
			case 2: t.addClass("listTreeIndent3");
			case 3: t.addClass("listTreeIndent4");
			case 4: t.addClass("listTreeIndent4");
		}
		flushSorter(null);
		untyped e.preventDefault();
	}
	
	function setIndent(t:JQuery, indent:Int)
	{
		t.removeClass("listTreeIndent1");
		t.removeClass("listTreeIndent2");
		t.removeClass("listTreeIndent3");
		t.removeClass("listTreeIndent4");		
		switch(indent)
		{
			case 1: t.addClass("listTreeIndent1");
			case 2: t.addClass("listTreeIndent2");
			case 3: t.addClass("listTreeIndent3");
			case 4: t.addClass("listTreeIndent4");
		}
		flushSorter(null);
	}
	
	function removeSeperator(e:Event)
	{
		var t = new JQuery(untyped e.currentTarget).parent();
		t.remove();
		flushSorter(null);
		untyped e.preventDefault();
	}
	
	function editHeading(e:Event)
	{
		var t = new JQuery(untyped e.currentTarget).parent().parent().find("p > span");
		t.html(untyped JsUtils.prompt("Name? Currently \""+t.html()+"\"."));
		
		flushSorter(null);
		untyped e.preventDefault();
	}
	
	function editItem(e:Event)
	{
		var t = new JQuery(untyped e.currentTarget).parent().find("span");
		t.html(untyped JsUtils.prompt("Name? Currently \"" + t.html() + "\"."));
		
		flushSorter(null);
		untyped e.preventDefault();
	}	
	
	function removeHeading(e:Event)
	{
		var t = new JQuery(untyped e.currentTarget).parent().parent();
		var items = t.find("li");
		setIndent(items.find("span"), 0);
		var s = "";
		
		for (n in 0...items.length) {
			s += "<li>"+items[n].innerHTML+"</li>";
		}
		
		var j = new JQuery("#siteViewHiddenSection");
		j.html(s+j.html());
		t.remove();
		
		refreshBehaviour();
		flushSorter(null);
		untyped e.preventDefault();
	}

	function flushSorter(e)
	{
		var dataPlace = new JQuery("#siteViewData");
		var m = new MenuDef();

		var j = new JQuery("#siteViewSection > li");
		for (n in 0...j.length) {
			if (j[n].className == "sectionSeperator") {
				m.addSeperator();
			}else {
				var headingName = new JQuery("p > span", j[n]).html();
				m.addHeading(headingName);
				var items = new JQuery("li", j[n]);

				for (n2 in 0...items.length) {
					var j2 = new JQuery("span", items[n2]);
					var itemName = j2.html();
					var tIndent = 0;
					if (j2.hasClass("listTreeIndent1")) tIndent = 1;
					if (j2.hasClass("listTreeIndent2")) tIndent = 2;
					if (j2.hasClass("listTreeIndent3")) tIndent = 3;
					if (j2.hasClass("listTreeIndent4")) tIndent = 4;
					
					var d = Unserializer.run(j2.attr("data"));
					var tId = d.id;
					var tType = d.type;
					
					m.addItem(tId, tType, itemName, headingName, tIndent);
				}
			}
		}
		
		dataPlace.val(Serializer.run(m));
	}
}