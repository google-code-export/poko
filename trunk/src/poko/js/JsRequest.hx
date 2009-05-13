/**
 * ...
 * @author ...
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