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

package site.cms.common;
import poko.Application;
import haxe.Unserializer;
import php.Lib;

class PageData
{
	public var id:Int;
	public var name:String;
	public var data:Dynamic;
	public var definition:Definition;
	
	public function new() {}
	
	public function loadById(id:Int)
	{
		var application = Application.instance;
		var result = application.db.requestSingle("SELECT p.id as 'id', d.name as 'name', d.id as 'definitionId', p.data as 'data' FROM `_pages` p, `_definitions` d WHERE p.`id`=\"" + application.db.cnx.escape(Std.string(id)) + "\" AND p.`definitionid`=d.`id`");
		init(result);
	}
	
	public function loadByName(name:String)
	{
		var application = Application.instance;
		var result = application.db.requestSingle("SELECT p.id as 'id', d.name as 'name', d.id as 'definitionId', p.data as 'data' FROM `_pages` p, `_definitions` d WHERE d.`name`=\"" + application.db.cnx.escape(name) + "\" AND p.`definitionid`=d.`id`");
		init(result);
	}
	
	private function init(result:Dynamic)
	{
		id = result.id;
		name = result.name;
		
		try {
			data = Unserializer.run(result.data);
		}catch (e:Dynamic) { 
			result.data = {}
		}
		
		definition = new Definition(result.definitionId);
	}
	
	public static function getPageById(id):PageData
	{
		var p = new PageData();
		p.loadById(id);
		return p;
	}
	
	public static function getPageByName(name:String):PageData
	{
		var p = new PageData();
		p.loadByName(name);
		return p;
	}
	
	public static function getPageNames():List<Dynamic>
	{
		var result = Application.instance.db.request("SELECT `name` FROM `_definitions` WHERE `isPage`='1' ORDER BY `order`");
		result = Lambda.map(result, function(item) {
			return Reflect.field(item, "name");
		});
		return result;
	}
	public static function getPages():List<PageData>
	{
		var result = Application.instance.db.request("SELECT `name` FROM `_definitions` WHERE `isPage`='1' ORDER BY `order`");
		var pages = new List();
		for (row in result)
			pages.add(getPageByName(row.name));
		
		return pages;
	}
}