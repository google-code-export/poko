
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