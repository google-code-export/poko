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

import haxe.remoting.Context;
import haxe.remoting.HttpConnection;
import php.Lib;
import poko.Poko;
import poko.views.Parse;
import poko.views.View;
import poko.system.Component;

class Controller 
{
	public var app:Poko;
	public var view:View;
	public var remoting:Context;
	public var components:List<Component>;
	public var identifier:String;
	
	public function new() 
	{
		app = Poko.instance;
		app.controller = this;
		
		view = new View();
		view.findTemplate(this, false);
		
		remoting = new Context();

		components = new List();
		
		var s = Type.getClassName(Type.getClass(this));
		identifier = s.substr(s.indexOf(".")+1);
	}
	
	public function handleRequest():Void
	{
		init();

		for (comp in components) comp.init();
		
		if (!HttpConnection.handleRequest(remoting)) 
		{
			main();
			
			for (comp in components) comp.main();
			
			render();
		}
		
		post();
		for (comp in components) comp.post();
		
		onFinal();
	}
	
	public function parse(tpl:String):String
	{
		return Parse.template(tpl, this);
	}
	
	public function render():Void
	{
		if (view.template != null)
			Lib.print(view.render());
	}
	
	public function setOutput(value):Void
	{
		view.setOutput(value);
	}
	
	public function init():Void{}
	
	public function main():Void{}
	
	public function post():Void { }
	
	public function onFinal():Void { }
}