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

package poko;

import php.FileSystem;
import templo.Loader;

class TemploContext implements TemploRenderable, implements Dynamic
{
	public var application:Application;
	public var controller:Dynamic;
	public var template_file:Dynamic;
	public var template_data:Dynamic;
	public var template_rendered:Bool;
	
	public function new(?template:String, ?data:Dynamic)  
	{
		Loader.OPTIMIZED = true;
		Loader.TMP_DIR = "./tpl/";
		Loader.MACROS = null;
		
		template_rendered = false;
		for (field in Reflect.fields(data)) Reflect.setField(this, field, Reflect.field(data, field));
		template_file = (template != null) ? template : "";
	}
	
	public function render():String
	{			
		if (template_rendered) return "";
		template_rendered = true;
		template_prepareData();
		return template_parseTemplate(template_file);
	}
	
	public function template_parseTemplate(template)
	{
		var tpl = "./tpl/" + StringTools.replace(template, "/", "__") + ".php";
		
		if(FileSystem.exists(tpl))
		{
			var loader:Loader = new Loader(template);
			return loader.execute(template_data);
		} else {
			throw "Template is missing: " + template;
			return null;
		}
	}
		
	public function template_prepareData()
	{
		template_data = { };
		
		// prepare dynamic properties (send in from constructor)
		var k = "";
		var v:Dynamic = null;
		untyped __php__("foreach($this->»dynamics as $k=> $v){ ");
				
			if (Std.is(v, TemploRenderable)) 
			{
				Reflect.setField(template_data, k, v.render());
			} else {
				Reflect.setField(template_data, k, v);
			}
		untyped __php__("}");
		
		// prepare class properties
		for (i in Reflect.fields(this))
		{
			if (Std.is(Reflect.field(this, i), TemploRenderable))
				Reflect.setField(template_data, i, Reflect.field(this, i).render());
			else
				Reflect.setField(template_data, i, Reflect.field(this, i));
		}
		
		template_data.application = Application.instance;
		template_data.controller = this;
	}
	
	public static function parse(tpl:String, ?data:Dynamic)
	{
		return new TemploContext(tpl, data).render();
	}
	
	public function toString():String
	{
		trace("[TemplateObject " + template_file + "]");
		return "";
	}
}

interface TemploRenderable 
{
	function render():String;
}
