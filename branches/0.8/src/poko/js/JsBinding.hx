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

package poko.js;

#if php

import php.Lib;
import poko.controllers.HtmlController;
import poko.Poko;
import haxe.Serializer;

class JsBinding
{
	public var jsRequest:String;
	
	public function new(jsRequest:String)
	{
		cast(Poko.instance.controller,HtmlController).jsBindings.set(jsRequest, this);
		this.jsRequest = jsRequest;
	}
	
	public function getCall(method:String, args:Array<Dynamic>):String
	{
		//if (args != null && Std.is(args, Array))
		//	args = Lib.toHaxeArray(cast args);
		
		var str = this + ".call('" + method + "', ";
		str += "'" +Serializer.run(args) + "'";
		str += ")";
		return str; 
	}
	
	public function queueCall(method:String, args:Array<Dynamic>, ?afterPageLoad=true):Void
	{
		var controller:HtmlController = cast Poko.instance.controller;
		var call = getCall(method, args);
		if (afterPageLoad)
		{
			controller.jsCalls.add(call);
		} else {
			// TODO make early JS call (after header - before body)
			controller.jsCalls.add(call);
		}
	}
	
	public function queueRawCall(method:String):Void
	{
		var controller:HtmlController = cast Poko.instance.controller;
		controller.jsCalls.add(this + "." + method);
	}
	
	public function getRawCall(method:String):String
	{
		return this + "." + method;
	}
	
	public function toString():String
	{
		return "poko.js.JsPoko.instance.resolveRequest('" + jsRequest + "')";
	}
}

#elseif js

/** Unserialized in JS with a refrence to the request - to resolve */

class JsBinding
{
	public var jsRequest:String;
	public function new(){}
}

#end