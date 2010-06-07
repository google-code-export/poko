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

import poko.utils.Messages;
import haxe.Public;
import haxe.Timer;
import php.db.Mysql;
import php.Lib;
import php.Session;
import php.Web;
import poko.Db;
import poko.User;

class Application 
{
	public static var instance:Application;
	
	public var request:Request;
	
	public var isDbRequired:Bool;
	public var db:Db;
	
	public var defaultRequest:String;
	
	public var siteRoot:String;
	public var sitePath:String;
	
	public var packageRoot:String;
	public var sitePackage:String;
	
	public var uploadFolder:String;
	
	public var useGip:Bool;
	
	public var html:String;
	
	public var skipAuthentication:Bool;

	public var params:Hash<Dynamic>;
	
	public var debug:Bool;
	
	public var sessionName:String;
	
	public var messages:Messages;
	public var user:User;
	
	public var errorContactEmail:String;
	
	public var components:List<Component>;
	private var time1:Float;

	public function new() 
	{
		time1= Timer.stamp();
		
		/**
		 Reduce the error levels
		 This hides a few slight bugs- like the options params bug.
		 If you have silent errors you might want to comment these out - very rare however. 
		*/
		//untyped __php__("error_reporting(E_ERROR);");
		//untyped __php__("set_error_handler('_hx_error_handler', E_ERROR);");
		
		sessionName = "poko";
		defaultRequest = "Index";
		
		instance = this;
		
		debug = false;
		
		sitePath = "";
		siteRoot = ".";
		
		packageRoot = siteRoot;
		sitePackage = "site";
		
		uploadFolder = "uploads";
		
		errorContactEmail = "contact@touchmypixel.com";
		
		useGip = false;
		skipAuthentication = false;
		
		db = new Db();
		isDbRequired = true;
		
		components = new List();
	}
	
	public function setupRequest():Void
	{
		setupSessionData();
		
		params = Web.getParams();
		
		var req:String = params.get("request");
		if (req == null) req = defaultRequest;
		
		var pack = sitePackage != "" ? sitePackage +".": "";
		var c:Class <Dynamic> = Type.resolveClass(pack + req);
		
		if (c == null) {
			Lib.print("<h3>404: Failed to load request</h3>Please check that the request is valid and it's class is imported.");
			php.Sys.exit(1);
		} /*else if (!Std.is(c, Request))
		{
			Lib.print("<h3>404: Not a valid request</h3>The class is not a valid request (extends Request).");
			php.Sys.exit(1);
		}*/
		else 
		{
			request = Type.createInstance(c, []);
			request.application = this;
		}
	}
	
	public function execute() 
	{
		if(request == null) setupRequest();
			
		var content = request.render();
		if (content != null) 
			Lib.print(content);
		
		finalizeSessionData(); 
		
		var time = Timer.stamp() - time1;
		//Lib.print(time);
	}
	
	private function setupSessionData():Void
	{
		if(Session.getName() != sessionName) Session.setName(sessionName);

		messages = Session.get("messages") ? Session.get("messages") : new Messages();
		user = Session.get("user") ? Session.get("user") : new User();
		user.update();
	}
	
	public function finalizeSessionData()
	{
		Session.set("messages", messages);
		Session.set("user", user);
	}
	
	public function redirect(url:String)
	{
		finalizeSessionData();
		Web.redirect(url);
		php.Sys.exit(1);
	}
}