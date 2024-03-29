package poko.system; 

import php.Web;
import php.Lib;
import php.Sys;

class Config
{
	// sets up a default
	public var useDb:Bool;
	// '?r=Controller' instead of 'request=Controller'
	public var useShortRequest:Bool;
	// uses simple url rewriting ie /?r=Index&p=10 -> /Index/p/10
	public var useUrlRewriting:Bool;
	// which base url does it rewrite to?
	public var urlRewriteBase:String;
	// if this is set to true, it is assumed that you're putting '?r=Home&p=5&d=hello'
	// as your URLs, otherwise it assumes rewritten ones ie '/home/p/5/d/hello'
	public var urlRewriteFromParams:Bool;
	// runs the router function in config
	public var useUrlRouting:Bool;
	
	// Whether or not the site is in development mode (i.
	public var development : Bool;
	public var isLive : Bool;
	public var printProcessingTime : Bool;
	
	public var controllerPackage : String;
	
	public var indexFile : String;
	public var indexPath : String;
	public var siteUrl : String;
	
	public var applicationPath : String;
	public var viewPath : String;
	
	public var permittedUriChars : String;
	public var logDateFormat : String;
	
	
	public var errorPage : String;
	public var error404Page : String;
	
	public var defaultController(getDefaultController, setDefaultController) : String;
	public var defaultAction : String;
	
	public var encryptionKey : String;
	
	public var sessionName : String;
	public var sessionHelper : SessionHelper;
	
	// if you want to have a MySQL by default
	public var database_host : String;
	public var database_port : String;
	public var database_user : String;
	public var database_password : String;
	public var database_database : String;
	
	public var serverRoot( getServerRoot, null ) : String;
	
	var _defaultController : String;
	/**
	 * Setting non-initialized constants.
	 */
	
	public var navigationExt:Hash<String>;
	 
	private function new(?debug : Dynamic)
	{
		var env = Sys.environment();
		
		applicationPath = Web.getCwd();
		
		navigationExt = new Hash();
		
		development = false;
		isLive = false;
		useShortRequest = false;
		useDb = true;
		useUrlRewriting = false;
		urlRewriteFromParams = false;
		printProcessingTime = false;
		
		// if it's not set, set a default
		if (sessionName == null) sessionName = "poko";
		
		// if we don't set a custom session helper, use the default
		if (sessionHelper == null) {
			sessionHelper = new SessionHelper(sessionName);
		}
		
		//indexFile
		if(env.exists('SCRIPT_NAME'))
		{
			var parts = env.get('SCRIPT_NAME').split("/");
			this.indexFile = parts[parts.length-1];
		}
		else
		{
			throw 'indexFile cannot be auto-detected. Please set it in "site/Config.hx".';
		}
		
		//indexPath
		if(env.exists('SCRIPT_NAME'))
		{
			var script = env.get('SCRIPT_NAME');
			this.indexPath = script.substr(0, script.lastIndexOf('/') + 1);
		}
		else
		{
			throw 'indexPath cannot be auto-detected. Please set it in "site/Config.hx".';
		}
		

		var index = this.indexPath + this.indexFile;
		
		// This works on Apache/PHP
		if(env.exists('HTTP_HOST'))
		{
			siteUrl = env.exists('HTTPS') && env.get('HTTPS') == 'on' ? 'https' : 'http';
			siteUrl += '://' + env.get('HTTP_HOST') + index;
		}
		else
		{
			// If no SSL and host detection, a short form of the URL is returned.
			siteUrl = index;
		}

		viewPath = this.applicationPath + 'views/';
		
		defaultController = 'Index';

		
		defaultAction = 'main';

		database_host = "localhost";
		database_port = "3306";
		database_user = "root";
		database_password = "";
		database_database = "";
		
		#if debug
			if (debug != null) this.dumpEnvironment(debug);
		#end
		
	}
	
	public function init() : Void {	}
	
	function getDefaultController() : String
	{
		return _defaultController;
	}
	
	function setDefaultController( value : String ) : String
	{
		_defaultController = value;
		return value;
	}
	
	function getServerRoot() : String
	{
		return siteUrl.substr( 0, siteUrl.lastIndexOf("/") + 1 );
	}

	///// Debug method //////////////////////////////////////////////
	
	#if debug
	public function dumpEnvironment(?logFile : Dynamic) : Void
	{
		var date = DateTools.format(Date.now(), '%Y-%m-%d %H:%M:%S');
		var output = '';
			
		output += "*** [" + date + "] Start of dump\n";

		output += "\nhaXigniter configuration:\n\n";
		for(field in Reflect.fields(this))
		{
			// Skip the sensitive parts
			if(field == 'encryptionKey') continue;
			
			output += field + ": '" + Reflect.field(this, field) + "'\n";
		}
		
		output += "\nhaXe web environment ";
		#if php
		output += "(PHP)";
		#elseif neko
		output += "(Neko)";
		#end
		output += ":\n\n";
		output += 'getCwd(): \'' + Web.getCwd() + "'\n";
		output += 'getHostName(): \'' + Web.getHostName() + "'\n";
		output += 'getURI(): \'' + Web.getURI() + "'\n";
		output += 'getParamsString(): \'' + Web.getParamsString() + "'\n";
		
		output += "\nServer environment:\n\n";
		for(field in Sys.environment().keys())
		{
			output += field + ": '" + Sys.environment().get(field) + "'\n";
		}

		#if php
		output += "\nPHP environment:\n\n";
		untyped __call__('ob_start');
		untyped __php__('foreach($_SERVER as $_dk => $_dv) echo "$_dk: \'$_dv\'\n";');
		output += untyped __call__('ob_get_clean');
		#end

		output += "\n*** End of dump";
		
		if(!Std.is(logFile, String))
		{
			output = '<hr><pre>' + output + '</pre><hr>';
			Lib.print(output);
		}
		else
		{
			#if php
			if(!php.FileSystem.exists(logFile))
				php.io.File.putContent(logFile, output);
			#elseif neko
			if(!neko.FileSystem.exists(logFile))
			{
				var file = neko.io.File.write(logFile, false);
				file.writeString(output);
				file.close();
			}
			#end
		}
	}
	#end
}
