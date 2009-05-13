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

import poko.utils.JsBinding;
import haxe.remoting.HttpAsyncConnection;
import haxe.Unserializer;
import js.Lib;

class JsRequest
{
	public var application:JsApplication;
	
	public var remoting:HttpAsyncConnection;
	
	public function new() 
	{	
	}
	
	public function init()
	{
		remoting = HttpAsyncConnection.urlConnect(Lib.window.location.href);
		remoting.setErrorHandler( function(e) trace("Remoting Error : " + Std.string(e)) );
	}
	
	/** Used to make a call which has been serialized and stored in the HTML of page */
	public function call(method, args:String)
	{
		var func = Reflect.field(this, method);
		
		if (func == null) {
			trace("Method does not exist: " + method);
			return;
		}
		
		var a = Unserializer.run(args);
		
		// Resolve Requests from JsBindings
		for (f in Reflect.fields(a))
		{
			var field = Reflect.field(a, f);
			if (Std.is(field, JsBinding))
				Reflect.setField(a, f, application.resolveRequest(field.jsRequest));
		}
		
		Reflect.callMethod(this, func, a);
	}
	
	/** Methods for getting string representations of calls to this request object */
	public function getThis():String
	{
		return "poko.js.JsApplication.instance.resolveRequest('" + Type.getClassName(Type.getClass(this)) + "')";
	}
	
	/** get a string of JS ready to make call */
	public function getCall(method:String, args:Array<Dynamic>):String
	{
		var str = getThis() + ".call('" + method + "', ";
		str += "'" +haxe.Serializer.run(args) + "'";
		str += ")";
		return str; 
	}
	
	/* get a string of Raw JS called on this object */
	public function getRawCall(str:String)
	{
		return getThis() + "." + str;
	}
	
	public function main()
	{
	}
	
}