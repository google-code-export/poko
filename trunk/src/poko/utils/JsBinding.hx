/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

 
package poko.utils;

#if php

import poko.Application;
import haxe.Serializer;

class JsBinding
{
	public var jsRequest:String;
	
	public function new(jsRequest:String)
	{
		Application.instance.request.jsBindings.set(jsRequest, this);
		this.jsRequest = jsRequest;
	}
	
	public function getCall(method:String, args:Array<Dynamic>):String
	{
		var str = this + ".call('" + method + "', ";
		str += "'" +Serializer.run(args) + "'";
		str += ")";
		return str; 
	}
	
	public function queueCall(method:String, args:Array<Dynamic>, ?afterPageLoad=true):Void
	{
		var call = getCall(method, args);
		if (afterPageLoad)
		{
			Application.instance.request.jsCalls.add(call);
		} else {
			// TODO make early JS call (after header - before body)
			Application.instance.request.jsCalls.add(call);
		}
	}
	
	public function getRawCall(method:String):String
	{
		return this + "." + method;
	}
	
	public function toString():String
	{
		return "poko.js.JsApplication.instance.resolveRequest('" + jsRequest + "')";
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