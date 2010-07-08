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

package poko;


#if php

import haxe.Timer;
import htemplate.Template;
import poko.controllers.Controller;
import php.Lib;
import php.Session;
import php.Web;
import poko.system.Db;
import poko.system.Url;
import poko.utils.PhpTools;

import site.Config;

class Poko 
{
	public static var instance:Poko;
	
	public var url:Url;
	public var config:Config;
	public var controller:Controller;
	public var params:Hash <Dynamic>;
	
	public var db(getDb, null):Db;
	private var __db:Db;
	
	public function new() 
	{
		//var time1 = Timer.stamp();
		/*
		var data = {one:"one", two:null}
		var ht = new Template("my template {:two} ");
		ht.execute(data);
		*/
		
		instance = this;
		
		PhpTools.setupTrace();
		
		config = new Config();
		config.init();
		
		var v="";
		if (Session.getName() != config.sessionName) 
		{
			v = "YAY";
			
			Session.setName(config.sessionName);
		}
		
		Session.start();

		params = Web.getParams();
		
		url = new Url(Web.getURI());
		
		var useURLRewrite = false;
		var controllerTypeName : String = useURLRewrite ? findControllerClassByRewrite() : findControllerClass();	
		var controllerType = Type.resolveClass( "site." + controllerTypeName );
		//var controllerType = Type.resolveClass("site." + findControllerClass());
		
		var is404 = false;
		if (controllerType != null)
		{
			controller = Type.createInstance(controllerType, []) ; 
			if (Std.is(controller, Controller))
				controller.handleRequest();
			else 
				is404 = true;
		} else {
			is404 = true;
		}
			
		if (is404) Lib.print("<font color=\"red\"><b>404: Not a valid request</b></font>");
			
		//var time = Timer.stamp() - time1;
		//Lib.print(time);
	}
	
	private function findControllerClassByRewrite():String
	{
		var request = params.get( "request" );
		var path = params.get( "path" );
		trace( request );
		trace( path );
		return "Index";
	}
	
	private function findControllerClass():String
	{
		var c:String = url.getSegments()[0] != "" ? url.getSegments()[0] : (params.get("request") != null ? params.get("request") : config.defaultController);

		if (c.lastIndexOf(".") != -1)
		{
			c = c.substr(0, c.lastIndexOf(".")+1) + c.substr(c.lastIndexOf(".")+1,1).toUpperCase() + c.substr(c.lastIndexOf(".")+2);
		} else {
			c = c.substr(0, 1).toUpperCase() + c.substr(1);
		}
		return c;
	}
	
	public function getDb()
	{
		if (__db == null) db = new Db();
		db.connect(config.database_host, config.database_database, config.database_user, config.database_password, config.database_port);
		return db;
	}
	
	public function redirect(url:String)
	{
		controller.onFinal();
		
		Web.redirect(url);
		php.Sys.exit(1);
	}
	
	static function main()
	{
		new Poko();
	}
}



#elseif js

import js.Dom;
import js.Lib;
import poko.js.JsPoko;

class Poko
{
	static private var app:JsPoko;
	
	public static function main() 
	{
        app = new JsPoko();
		app.serverUrl = "http://localhost/fwork/";
		
		js.Lib.window.onload = run;
	}
	
	public static function run(e:Event)
	{
		app.run();
	}
}

#end