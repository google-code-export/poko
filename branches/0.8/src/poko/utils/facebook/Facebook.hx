package poko.utils.facebook;
import php.Lib;

import hxjson2.JSON;
import php.Web;

using StringTools;
using Lambda;

class Facebook {
	
	//do some magic
	static function __init__()
		untyped __php__("require 'inc/facebook.php'")
		
	var inst:Dynamic;
	
	public function new(appId:String,secret:String,?cookie:Bool,?domain:String,?fileUpload:String) {
		var config = new Hash<Dynamic>();
		config.set("appId",appId);
		config.set("secret",secret);
		if (cookie!=null)
			config.set("cookie",cookie);
		if (domain!=null)
			config.set("domain",domain);
		if (fileUpload!=null)
			config.set("fileUpload",fileUpload);
		inst = untyped __call__('new Facebook',Lib.associativeArrayOfHash(config));
	}
	
	public function getSession():FbSession {
		var d:Dynamic = inst.getSession();
		return if (d==null)
			null;
		else
			this.buildObject({},d);
	}
	
	public function setSession(session:FbSession,?cookie:Bool=true) {
		var h = new Hash<Dynamic>();
		for (f in Reflect.fields(session))
			h.set(f,Reflect.field(session,f));
		inst.setSession(Lib.associativeArrayOfHash(h),cookie);
	}
	
	inline function isIndexed(d:Dynamic):Bool
		return untyped __physeq__(__call__('array_values',d),d)
	
	inline function isArray(d:Dynamic):Bool
		return untyped __call__('is_array',d)
		
	function getValue(d:Dynamic)
		return if (this.isArray(d))
			this.buildObject({},d)
		else
			d
	
	function buildObject(dest:Dynamic,source:Dynamic):Dynamic {
		untyped {
			__php__('foreach ($source as $key=>$value) {');
				if (!this.isArray(value)) {
					Reflect.setField(dest,key,value);
				}
				else {
					if (this.isIndexed(value)) {
						var a = new Array<Dynamic>();
						__php__('foreach ($value as $v){');
							a.push(this.getValue(v));
						__php__('}');
						Reflect.setField(dest,key,a);
					}
					else
						Reflect.setField(dest,key,this.buildObject({},value));
				}			
			__php__('}');		
		}
		return dest;
	}
	
	public inline function api<T>(query:String):T
		return this.buildObject({},inst.api(query))
	
	public function apiExt<T>(query:FbQuery):T
	{
		return this.buildObject( { }, inst.api(query._path, query._method, Lib.associativeArrayOfHash(query.getParams())));
	}
		
	public inline function getUser():Int
		return inst.getUser()
		
	public inline function getLogoutUrl():String
		return inst.getLogoutUrl()
		
	public inline function getLoginUrl(?nextUrl:String, ?cancelUrl:String, ?permissions:Array<String>, ?canvas:Bool = true):String
	{
		var h:Hash<Dynamic> = new Hash();
		
		h.set('canvas', canvas ? 1 : 0);
		h.set('fbconnect', canvas ? 0 : 1);
		
		if (nextUrl != null) h.set('next', nextUrl);
		if (cancelUrl != null) h.set('cancel_url', cancelUrl);
		if (permissions != null) h.set('req_perms', permissions.join(','));
		
		return inst.getLoginUrl(Lib.associativeArrayOfHash(h));
	}
	
	/* --------------------------------------------------- */

	public inline function apiGetMe():FbDataUser
	{
		return api('/me');
	}
	
	public inline function apiGetUser(idOrUsername:String):FbDataUserPublic
	{
		return api('/' + idOrUsername);
	}
	
	public function apiPublishToStream()
	{
		
	}
	
	public function apiGetRequests() {
		return api("/me/apprequests");
	}
	public function apiGetRequestsForMe() {
		return api("/me/apprequests");
	}
	
	/* --------------------------------------------------- */
	
	public static function getSignedRequest(?signedRequest:String = null):FbSignedData
	{
		if (signedRequest == null) signedRequest = untyped __var__('_REQUEST', 'signed_request');
		try {
			if (signedRequest != null) {
				var a = signedRequest.split(".");
				var encodedSig = a[0];
				var payload = a[1];
				var sig = base64Decode(encodedSig.replace('-', '+').replace('_', '/'));
				var data:FbSignedData = cast JSON.decode(base64Decode(payload.replace('-', '+').replace('_', '/')));
				data.signedRequest = signedRequest;
				return data;
			}
		}catch(e:Dynamic){}
		return null;
	}
	
	public static inline function base64Decode(s:String):String
	{
		return untyped __call__("base64_decode", s);
	}
}

// -----------------------------------------------------------------------------------------

typedef FbSignedData = {
	// general
	var algorithm:String;
	var expires:Float;
	var issued_at:Float;
	var oauth_token:String;
	var user:FbSignedDataUser;
	var user_id:String;
	var signedRequest:String;
	
	// tab only
	var page: {
			liked:Bool,
			id:Int
		};
}

typedef FbSignedDataUser = {
	var country:String;
	var local:String;
	var age:FbSignedDataUserAge;
	var user_id:FbSignedDataUserAge;
}

typedef FbSignedDataUserAge = {
	var min:Float;
}

typedef FbDataUser = {
	var id:String;
	var name:String;
	var first_name:String;
	var last_name:String;
	var link:String;
	var username:String;
	var gender:String;
	var local:String;
	var languages:Array<{id:String, name:String}>;
	var verified:Bool;
	var updated_time:String;
}

typedef FbDataUserPublic = {
   var id:String;
   var name:String;
   var first_name:String;
   var last_name:String;
   var locale:String; // ie en_US
   var username:String; // NOT AL
   var gender:String; // NOT ALL
   var link:String; // NOT ALL, link to profile page
}

enum FbQueryType {
	None;
	StreamPublish;
}

// queries
class FbQuery
{
	public static inline var _QUERY_TYPE_POST:String = "POST";
	public static inline var _QUERY_TYPE_GET:String = "GET";
	
	public var _type:FbQueryType;
	public var _method:String;
	public var _path:String;
	
	public function new()
	{
		_type = FbQueryType.None;
		_path = null;
		_method = _QUERY_TYPE_GET;
	}
	
	public function getParams():Hash<String>
	{
		var h = new Hash();
		for (k in Reflect.fields(this)) {
			if (k.substr(0, 1) != '_')
				if (Reflect.field(this, k) != null) h.set(k, Reflect.field(this, k));
		}
		return h;
	}
}

class FbQueryStreamPublish extends FbQuery
{
	public var message:String;
	public var picture:String;
	
	public var link:String;
	public var name:String;
	public var caption:String;
	
	public function new(_idOrUsername:String, _message:String, ?_picture:String, ?_link:String, ?_name:String, ?_caption:String)
	{
		super();
		
		_type = FbQueryType.StreamPublish;
		_method = FbQuery._QUERY_TYPE_POST;
		_path = "/feed";
		
		message = _message;
		picture = _picture;
		link = _link;
		name = _name;
		caption = _caption;
	}
}