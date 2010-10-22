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

package poko.views;
import haxe.Stack;
import php.Lib;
import poko.Poko;
import php.FileSystem;

using StringTools;

//import poko.views.renderers.IRenderer;

class View implements Renderable
{
	public var type:ViewType;
	public var rendered:Bool;
	public var template:String;
	public var data:Dynamic;
	public var renderCache:String;
	public var renderer:Dynamic;
	public var scope:Dynamic;
	
	public function new(?scope:Dynamic, ?type:ViewType, ?template:String, ?data:Dynamic)
	{
		if (type == null) type = ViewType.PHP;
		this.type = type;
		this.template = template;
		this.data = data != null ? data : {};
		this.scope = scope != null ? scope : Poko.instance.controller;
		
		rendered = false;
	}
	
	public function render():String 
	{
		if (template == null || rendered) return renderCache;
		
		rendered = true;

		// cant get IRenderable to work.. maybe a bug?
		renderer = switch(type)
		{
			case ViewType.PHP: cast new poko.views.renderers.Php(template);
			case ViewType.TEMPLO: cast new poko.views.renderers.Templo(template);
			case ViewType.HTEMPLATE: cast new poko.views.renderers.HTemplate(template);
		}
		
		/*
		 * Populate 'data' object with scope's fields
		 * then render any fields which are 'Renderable'
		 */
		
		for (i in Reflect.fields(scope))
		{
			var d:Dynamic = Reflect.field(scope, i);
			if (d != Poko.instance && d != Poko.instance.controller)
			{
				Reflect.setField(data, i, d);
			}
		}
		
		for (i in Reflect.fields(data))
		{
			var d:Dynamic = Reflect.field(data, i);
			
			if (Std.is(d, Renderable))
				d = cast(d, Renderable).render();
			
			renderer.assign(i, d);
		}
		
		renderer.assign("app", Poko.instance);
		renderer.assign("controller", Poko.instance.controller);
		renderer.assign("resolveClass", Type.resolveClass);
		
		return renderCache = renderer.render();
	}
	
	
	/*public function renderWithData(data:Dynamic)
	{
		if (renderer == null) createRenderer();
		renderer.data = { };
		for (i in Reflect.fields(data))
		{
			renderer.assign(i, Reflect.field(data, i));
		}
		renderer.assign("application", Poko.instance);
		renderer.assign("controller", Poko.instance.controller);
		return renderCache = renderer.render();
		
	}
	*/
	
	
	public function findTemplate(controller:Dynamic, ?skipTopLevel:Bool=false):Void
	{
		var file = "";
		
		var c:Class<Dynamic> = skipTopLevel ? Type.getSuperClass(Type.getClass(controller)) : Type.getClass(controller);
		
		while (c != null) 
		{
			file = Std.string(c);
			if (StringTools.startsWith(file, "site."))
			{
				file = StringTools.replace(file.substr("site.".length),".","/");
				
				var checkTemplo = "./tpl/mtt/" + file.replace("/", "__") + ".mtt.php";
				var checkPhp = "./tpl/php/" + file + ".php";
				var checkHTemplate = "./tpl/ht/" + file + ".ht";
				
				if (FileSystem.exists(checkTemplo))
				{
					template = file + ".mtt";
					type = ViewType.TEMPLO;
					return;
				}
				
				if (FileSystem.exists(checkPhp))
				{
					template = file + ".php";
					type = ViewType.PHP;
					return;
				}
				
				if (FileSystem.exists(checkHTemplate))
				{
					template = file + ".ht";
					type = ViewType.HTEMPLATE;
					return;
				}
			}
			c = Type.getSuperClass(c);	
		}
		
		template = null;
	}
	
	public function getExt()
	{
		return switch(type)
		{
			case ViewType.HTEMPLATE: "ht";
			case ViewType.TEMPLO: "mtt";
			case ViewType.PHP: "php";
		}
	}
	
	public function setOutput(s:String)
	{
		renderCache = s;
		rendered = true;
	}
	
	public function toString()
	{
		return "[View "+template+"]";
	}
}

enum ViewType {
	PHP;
	TEMPLO;
	HTEMPLATE;
}