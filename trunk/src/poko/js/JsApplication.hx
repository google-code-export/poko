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

import js.Lib;

class JsApplication
{
	public static var instance:JsApplication;
	public var params:Hash<String>;
	public var requests:Hash<JsRequest>;
	
	public var serverUrl:String;
	
	private var requestBuffer:List<String>;
	
	public function new() 
	{
		instance = this;
		
		requests = new Hash();
		requestBuffer = new List();
		
		serverUrl = "";
		setupLog();
		parseParams();
	}
	
	public function run()
	{		
		for (req in requests)
			req.main();
	}
	
	public function resolveRequest(req:String):JsRequest
	{
		return requests.get(req);
	}
	
	public function setupRequest(req:String):JsRequest
	{
		var request:JsRequest = null;
		var c = Type.resolveClass(req);
		if (c != null) {
			request = Type.createInstance(c, []);
			request.application = this;
			request.init();
		}
		return request;
	}
	
	public function addRequest(req:String):JsRequest
	{
		var r = setupRequest(req);
		if (r != null)
			requests.set(req, r);
		
		return r;
	}
	
	function parseParams()
	{
		if(params == null) params = new Hash();
		var parts = Lib.window.location.search.substr(1).split("&");
		for (part in parts)
		{
			var p = part.split("=");
			params.set(p[0], p[1]);
		}
	}	
	
	public static function setupLog() 
	{
		haxe.Log.trace = log;
	}
	
	public static function log ( v:Dynamic, ?pos : haxe.PosInfos ):Void 
	{
		var console = Reflect.field( js.Lib.window, "console" );
		
		//console.log( "%s->%s->%s->line(%s) : %o \n", pos.fileName, pos.className, pos.methodName, pos.lineNumber, v );
		console.log( "%s:%s: %o \n", pos.fileName, pos.lineNumber, v );
	}
}