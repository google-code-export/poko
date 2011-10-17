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

package poko.controllers;

import poko.Poko;
import poko.utils.html.ScriptList;
import poko.utils.PhpTools;
import haxe.remoting.Context;
import haxe.remoting.HttpConnection;
import php.FileSystem;
import php.Lib;
import php.Session;
import php.Sys;
import php.Web;
import poko.js.JsBinding;
import poko.utils.StringTools2;
import poko.views.View;

class HtmlController extends Controller
{
	public var head:HtmlHeader;
		
	public var jsBindings:Hash<JsBinding>;
	public var jsCalls:List<String>;
	
	public var layoutView:View;
	
	public function new()
	{
		super();
		
		head = new HtmlHeader();
		head.title = "haxe poko";
		
		jsBindings = new Hash();
		jsCalls =  new List();
		
		layoutView = new View();
		layoutView.findTemplate(this, true);
	}
	
	override public function render():Void
	{
		if (layoutView.template != null)
		{
			Lib.print(layoutView.render());
		} else {
			super.render();
		}
	}
	
	override public function setOutput(value)
	{
		layoutView.setOutput(value);
	}
	
	public function setContentOutput(value)
	{
		view.setOutput(value);
	}
	
	public function nl2br(input:String):String
	{
		#if php
			return untyped __call__("nl2br", input);
		#else
			return StringTools.replace(input, "\n", "<br />");
		#end
	}
	
	public function url(u:String)
	{
		var c = Poko.instance.config;
		if (c.useUrlRewriting) {
			if (c.urlRewriteFromParams) {
				// convert params to slashes
				// assume '?r=Home&d=1' -> '/home/d/1'
				var o:String = '';
				var a:String = null;
				untyped __php__('parse_str(substr($u, 1), $a);');
				untyped __php__("foreach($a as $k=>$v){ if ($k != 'r' and $k != 'request'){ $o .= $k . '/' . $v . '/'; }else{ $o .= $v . '/'; } }");
				return c.urlRewriteBase + '/' + o.substr(0, 1).toLowerCase() + o.substr(1);
			}else{
				return c.urlRewriteBase + u;
			}
		}else {
			if (c.urlRewriteFromParams) {
				return u;
			}else{
				// convert slashes to params
				// assume '/home/d/1' -> '?r=Home&d=1
				var a = u.split('/');
				var rAdded = false;
				var c = 0;
				var o = null;
				while (c < a.length) {
					if (a[c] != '') {
						if (!rAdded) {
							o = '?r=' + StringTools2.ucFirst(a[c]);
							rAdded = true;
						}else {
							o += '&' + a[c] + '=' + a[c + 1];
							c++;
						}
					}
					c++;
				}
				return o;
			}
		}
	}
	
}

class HtmlHeader extends ScriptList
{	
	public var title:String;
	public var description:String;
	public var meta:String;
	public var keywords:String;
	public var publisher:String;
	public var date:String;
	public var favicon:String;
	public var author:String;
	
	public var js:List<String>;
	public var css:List<String>;
	public var cssPrint:List<String>;
	public var cssIe6:List<String>;
	public var cssIe7:List<String>;
		
	
	public function new()
	{
		super();
		
		title = description = meta = keywords = publisher = date = "";
		js = new List();
		css = new List();
		cssPrint = new List();
		cssIe6 = new List();
		cssIe7 = new List();
	}
	
	public function getJs()
	{
		var str = "";
		for (jsItem in js) 
			str += "<script type=\"text/javascript\" src=\""+ jsItem +"\" ></script> \n";
			
		
		var jsBindings = cast(Poko.instance.controller, HtmlController).jsBindings;
		for(jsBinding in jsBindings.keys())
			str += "<script> poko.js.JsPoko.instance.addRequest(\"" + jsBinding + "\") </script> \n";
		
		return str;
	}
	
	public function getJsCalls()
	{
		var str = "";
		var jsCalls = cast(Poko.instance.controller, HtmlController).jsCalls;
		for(jsCall in jsCalls)
			str += "<script> "+jsCall+" </script> \n";
		return str;
	}
	
	public function getCssIe6()
	{
		var str = "<!--[if lte IE 6]>";
		for (cssItem in cssIe6) 
			str += "<script type=\"text/javascript\" src=\"" + cssItem +"\" ></script> \n";
			
		str += "<![endif]-->";
		return str;
	}
	
	public function getCssIe7()
	{
		var str = "<!--[if IE 7]>";
		for (cssItem in cssIe7) 
			str += "<script type=\"text/javascript\" src=\"" + cssItem +"\" ></script> \n";
		
		str += "<![endif]-->";
		return str;
	}
	
	public function getCss()
	{
		var str = "";
		for (cssItem in css) 
			str += "<link rel=\"stylesheet\" href=\"" + cssItem + "\" type=\"text/css\" /> \n";
		return str;
	}
	
	public function getCssPrint()
	{
		var str = "";
		for (cssItem in cssPrint) 
			str += "<link rel=\"stylesheet\" href=\"" + cssItem + "\" type=\"text/css\" media=\"print\" /> \n";
		return str;
	}
}