package poko.system; 

import php.Web;
import php.Lib;
import php.Sys;

class Config
{
	public var development : Bool;
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
	
	public var defaultController : String;
	public var defaultAction : String;
	
	public var encryptionKey : String;
	public var sessionName : String;
	
	public var database_host : String;
	public var database_port : String;
	public var database_user : String;
	public var database_password : String;
	public var database_database : String;
	
	/**
	 * Setting non-initialized constants.
	 */
	private function new(?debug : Dynamic)
	{
		var env = Sys.environment();
		
		
		applicationPath = Web.getCwd();
		
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
		
		sessionName = "poko";
		
		if (debug != null) this.dumpEnvironment(debug);
		
	}

	///// Debug method //////////////////////////////////////////////
	
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
}
