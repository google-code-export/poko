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
package site.cms.modules.base.js;

import poko.js.JsRequest;
import haxe.Serializer;
import haxe.Unserializer;
import js.Lib;

class JsDatasetItem extends JsRequest
{	
	public var valueHolder:JQuery;
	public var table:JQuery;
	public var properties:Dynamic;
	public var id:String;
	
	override public function main()
	{
		var el = new JQuery("#form1___cancel").click(function(e){
			Lib.window.history.back();
		});
	}
	
	public function showHideElements(elements:String, value:Bool, hideOnValue:Bool)
	{
		if(elements != null){
			var els = elements.split(",");
			for (el in els) {
				var e = new JQuery("label[for=form1_" + el + "]").parent().parent();
				value == hideOnValue ? e.hide() : e.show();
			}
		}
	}
	
	public function setupShowHideElements(affector:String, elements:String, value:Bool, hideOnValue:Bool)
	{
		showHideElements(elements, value, hideOnValue);
		
		var _elements = elements;
		var _hideOnValue = hideOnValue;
		var _affector = affector;
		var _t = this;
		
		var e = new JQuery("input[name=form1_" + affector + "]").change(function(e){
			_t.showHideElements(_elements, new JQuery("input[name=form1_" + affector + "]:checked").val(), _hideOnValue);
		});
	}
	
	public function flushWymEditors()
	{
		var c = 0;
		while(c > -1){
			var editor = JQuery.wymeditors(c);
			if (editor == null) {
				c = -1;
			}else {
				c++;
				editor.update();
			}
		}
		return(true);
	}
}