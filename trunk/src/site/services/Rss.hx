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

package site.services;

import poko.Service;
import php.Lib;
import php.Sys;
import php.Web;
import poko.Request;

class Rss extends Service
{
	public var title:String;
	public var description:String;
	public var generator:String;
	public var link:String;
	public var items:Dynamic;
	
	public function new() 
	{
		super();
	}
	
	override public function pre()
	{
		super.pre();
		
		allowedMethods.add("news");
	}
	
	public function news()
	{
		data.title = "Scarygirl";
		data.description = "Scarygirl " + method;
		data.generator = "haXe php (haXe.org)";
		data.link = "http://www.scarygirl.com/";
		
		data.items = application.db.request("SELECT * FROM `news` ORDER BY `order`");
		data.items = Lambda.map(data.items, formatNews);
	}
	
	private function formatNews(v:Dynamic):Dynamic
	{
		var item:Dynamic = { };
		item.title = v.title + " - " + v.date;
		item.description = v.content;
		item.link = "http://scarygirl.com";
		item.guid = v.id;
		item.pubDate = v.updated;
		
		return item;
	}
}