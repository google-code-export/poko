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

import poko.utils.PhpTools;
import haxe.remoting.Context;
import haxe.remoting.HttpConnection;
import php.FileSystem;
import php.Lib;
import php.Session;
import php.Sys;
import php.Web;
import poko.js.JsBinding;
import poko.ViewContext;

class Request extends ViewContext
{
	public var authenticate:Bool;
	public var authenticationRequired:Array<String>;
	
	public var request_output:String;
	public var request_content:String;
	public var request_content_file:String;

	public var head:HtmlHeader;
	public var js:List<String>;
	public var css:List<String>;
	public var cssPrint:List<String>;
	
	public var remoting:Context;
	
	public var jsBindings:Hash<JsBinding>;
	public var jsCalls:List<String>;
	
	public function new() 
	{
		super();
		
		js = new List();
		css = new List();
		cssPrint = new List();
		head = new HtmlHeader();
		head.title = "haxe poko";
		
		jsBindings = new Hash();
		jsCalls =  new List();
		
		if (Type.getSuperClass(Type.getClass(this)) == Request || Type.getSuperClass(Type.getClass(this)) == Service)
		{
			// Request that doesnt extend a layout
		
			template_file = findTemplate(Type.getClassName(Type.getClass(this)));
		} 
		else 
		{
			// Request extending a layout
		
			var c:Class<Dynamic> = Type.getClass(this);
			
			while (Type.getSuperClass(c) != Request) c = Type.getSuperClass(c);
			
			template_file = findTemplate(Type.getClassName(c));
			
			request_content_file =  findTemplate(Type.getClassName(Type.getClass(this))); 
		}
		
		authenticate = false;
	}
	
	private function findTemplate(name:String)
	{
		var n = StringTools.replace(name, ".", "__");
		if (FileSystem.exists("tpl/" + n + ".mtt.php")) return n + ".mtt";
		if (FileSystem.exists("tpl/" + n + ".php")) return n + ".php";
		return "";	
	}
	
	/**  set the 'content' to be parsed */
	public function setContentTemplate(file:String):Void
	{
		request_content_file = Application.instance.sitePackage+ "/" + file;
	}
	
	/**  set the 'template' to be parsed */
	public function setRequestTemplate(file:String):Void
	{
		template_file = Application.instance.sitePackage+ "/" + file;
	}
	
	/**  Override content to be included in the request template by includeRequest() global function */
	public function setContentOutput(output)
	{
		request_content = output;
	}
	
	/**  Override all output sent to the browser */
	public function setOutput(output)
	{
		request_output = output;
	}
	
	/**  set up the request */
	public function init()
	{
		if (application.params.get("logout") == "true") 
		{
			Session.set("authenticated", null);
			Web.redirect("?request=cms.Index");
		}		
		
		remoting = new Context();
	}
	
	/**  fired before authentication */
	public function pre() { }
	
	/* check request authentication */
	public function auth():Bool
	{
		if (!application.user.authenticated) return false;
		if (authenticationRequired != null && authenticationRequired.length > 0) {
			for (s in application.user.groups) {
				if (Lambda.has(authenticationRequired, s)) return true;
			}
			return false;
		}
		return true;
	}
	
	/**  main request event */
	public function main() { }
	
	/**  run just before rendering event */
	public function preRender() { }	
	
	/**  fired after request render */
	public function post(){}
	
	
	/**  render the request request event */
	override public function render():String
	{
		init();
		
		if (application.isDbRequired)
			if (application.db.cnx == null)
				throw("You have not setup a DB connection and you application states that one is required");
		
		pre();
		
		for (component in application.components) component.pre();
		
		if (!application.skipAuthentication && (authenticate && !auth()))
		{
			application.redirect("?request=cms.Index");
			//request_output = "Authentication Failed";
		} 
		else
		{
			if (!HttpConnection.handleRequest(remoting)) 
			{
				main();
				
				for (component in application.components) component.main();
				
				if (request_output == null) 
				{
					//var tpl = "./tpl/" + StringTools.replace(template_file, "/", "__");
					//if(FileSystem.exists(tpl))
					//{
						preRender();
						request_output = super.render();
					//} else {
					//	throw ("No content set, falling back to template mode - Cannot find your Requests's template file: '" + tpl + "'");
					//}
				}
			}
		}
		
		post();
		
		return request_output;
	}

	public function getRequestContent()
	{
		if (request_content != null)
		{
			return request_content;
		} else {
			return template_parseTemplate(request_content_file);
		}
	}
	
	public function nl2br(input:String):String
	{
		#if php
			return untyped __call__("nl2br", input);
		#else
			return StringTools.replace(input, "\n", "<br />");
		#end
	}
	
}

class HtmlHeader 
{	
	public var title:String;
	public var description:String;
	public var meta:String;
	public var keywords:String;
	public var publisher:String;
	public var date:String;
	public var favicon:String;
	public var author:String;
	
	public var js:List<String>;
	public var css:List<String>;
	public var cssPrint:List<String>;
	public var cssIe6:List<String>;
	public var cssIe7:List<String>;
	
	
	public function new()
	{
		title = description = meta = keywords = publisher = date = "";
		js = new List();
		css = new List();
		cssPrint = new List();
		cssIe6 = new List();
		cssIe7 = new List();
	}
	
	public function getJs()
	{
		var str = "";
		for (jsItem in js) 
			str += "<script type=\"text/javascript\" src=\""+ jsItem +"\" ></script> \n";
			
		var jsBindings = Application.instance.request.jsBindings;
		for(jsBinding in jsBindings.keys())
			str += "<script> poko.js.JsApplication.instance.addRequest(\"" + jsBinding + "\") </script> \n";
		
		return str;
	}
	
	public function getJsCalls()
	{
		var str = "";
		var jsCalls = Application.instance.request.jsCalls;
		for(jsCall in jsCalls)
			str += "<script> "+jsCall+" </script> \n";
		return str;
	}
	
	public function getCssIe6()
	{
		var str = "<!--[if lte IE 6]>";
		for (cssItem in cssIe6) 
			str += "<script type=\"text/javascript\" src=\"" + cssItem +"\" ></script> \n";
			
		str += "<![endif]-->";
		return str;
	}
	
	public function getCssIe7()
	{
		var str = "<!--[if IE 7]>";
		for (cssItem in cssIe7) 
			str += "<script type=\"text/javascript\" src=\"" + cssItem +"\" ></script> \n";
		
		str += "<![endif]-->";
		return str;
	}
	
	public function getCss()
	{
		var str = "";
		for (cssItem in css) 
			str += "<link rel=\"stylesheet\" href=\"" + cssItem + "\" type=\"text/css\" /> \n";
		return str;
	}
	
	public function getCssPrint()
	{
		var str = "";
		for (cssItem in cssPrint) 
			str += "<link rel=\"stylesheet\" href=\"" + cssItem + "\" type=\"text/css\" media=\"print\" /> \n";
		return str;
	}	
}