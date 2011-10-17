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

package poko.views.renderers;

import php.FileSystem;
//import poko.views.renderers.IRenderer;
import poko.views.Renderable;
import templo.Loader;
using StringTools;

class Templo implements Renderable
{
	public var template:String;
	public var data:Dynamic;
	
	public function new(?template:String ) 
	{
		this.template = template;
	
		Loader.OPTIMIZED = true;
		Loader.TMP_DIR = "./tpl/mtt/";
		Loader.MACROS = null;
		
		data = { };
	}
	
	public function render():String
	{			
		var tpl = template.replace("/", "__");
		
		if(FileSystem.exists("./tpl/mtt/"+tpl+".php"))
		{
			var loader:Loader = new Loader(tpl);
			return loader.execute(data);
		} else {
			throw "Templo Template is missing: " + template;
			return null;
		}
		return "";
	}
	
	public function assign(field:String, value:Dynamic):Void
	{
		Reflect.setField(data, field, value);
	}

	public static function parse(template:String, ?data:Dynamic):String
	{
		var renderer = new Templo(template);
		renderer.data = data;
		return renderer.render();
	}
	
	public function toString():String
	{
		return "[TemploView " + template + "]";
	}
	
}

