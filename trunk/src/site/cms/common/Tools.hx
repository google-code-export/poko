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
import poko.Poko;

class Tools 
{

	public function new() 
	{
		
	}
	
	public static function getDBTables():List<Dynamic>
	{
		var application = Poko.instance;
		
		var tables:List < Dynamic > = Poko.instance.db.request("SHOW TABLES");
		var app = application;
		
		tables = Lambda.map(tables, function(table) {
			return Reflect.field(table, "Tables_in_" + app.db.database);
		});
		
		tables = Lambda.filter(tables, function(table) {
			return table.substr(0, 1) != "_";
		});
		
		return tables;
	}
	
	public static function parseWymImages(data:String)
	{
		var out = "";
		
		var a = data.split("<img");
		var a2 = [];
		
		// first is blank or data before image
		
		for (s in a)
		{
			var p = s.indexOf("/>");
			if(p != -1){
				var s1 = "<img" + s.substr(0, p + 2);
				var s2 = s.substr(p + 2, s.length - p);
				a2.push(parseWymImagesToken(s1));
				a2.push(s2);
			}else {
				a2.push(s);
			}
		}
		
		for (s in a2)
		{
			out += s;
		}
		
		return(out);
	}
	
	public static function parseWymImagesToken(data:String)
	{
		var l0, i1, i2, i3, i4, i5, i6;
	
		l0 = 0;
		i1 = -1;
		i2 = -1;
		i3 = -1;
		i4 = -1;
		i5 = -1;
		i6 = -1;		
		
		var offset = 0;
		var out = "";
		
		l0 = data.indexOf('<img', offset);
		i1 = data.indexOf('src="', offset) + 5;
		
		if(i1 != -1){
			i2 = data.indexOf('"', i1);
			i3 = data.indexOf('width="', offset) + 7;
			if(i3 != -1){
				i4 = data.indexOf('"', i3);
				i5 = data.indexOf('height="', offset) + 8;
				if (i5 != -1) i6 = data.indexOf('"', i5);
			}
		}

		if (i6 != -1) {
			var n = data.length;
			
			var s = data.substr(i1, i2 - i1);
			var i7 = s.indexOf('res/media/galleries/', offset) + 20;
			if(i7 != -1){
				s = s.substr(i7, s.length - i7);
			}
			
			var w = data.substr(i3, i4 - i3);
			var h = data.substr(i5, i6 - i5);
			
			out += data.substr(offset, l0 - offset) + '<img src="./?r=I&s=' + s + '&w=' + w + '&h=' + h + '&p=media" width="' + w + '" height="' + h + '" />';
			
			var i8 = data.indexOf('/>', offset) + 2;
			offset = i8;
		}
		
		return out;
	}
}