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

package poko.utils;

class ListData 
{

	public static function getDays(?reverse = true)
	{
		var data:List<Dynamic> = new List();
		
		for (i in 1...31+1) {
			var n = i;
			data.add( { key:Std.string(n), value:Std.string(n) } );
		}
		
		return(data);
	}
	
	public static var months_short = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	public static var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	
	public inline static function getMonths(?short = false)
	{
		return short ? arrayToList(months_short, 1) : arrayToList(months, 1);
	}
	
	public static function getYears(from:Int, to:Int, ?reverse = true):List<Dynamic>
	{
		var data:List<Dynamic> = new List();
		
		if (reverse){
			for (i in 0...(to-from+1)) {
				var n = to - i;
				data.add( { key:Std.string(n), value:Std.string(n) } );
			}
		}else {
			for (i in 0...(to-from+1)) {
				var n = from + i;
				data.add( { key:Std.string(n), value:Std.string(n) } );
			}
		}
		return(data);
	}
	
	/*public static function getLetters(uppercase=false){
		if (uppercase) return(array(a=>"A", b=>"B", c=>"C", d=>"D", e=>"E", f=>"F", g=>"G", h=>"H", i=>"I", j=>"J", k=>"K", l=>"L", m=>"M", n=>"N", o=>"O", p=>"P", q=>"Q", r=>"R", s=>"S", t=>"T", u=>"U", v=>"V", w=>"W", x=>"X", y=>"Y", z=>"Z"));
		return(array(a=>"a", b=>"b", c=>"c", d=>"d", e=>"e", f=>"f", g=>"g", h=>"h", i=>"i", j=>"j", k=>"k", l=>"l", m=>"m", n=>"n", o=>"o", p=>"p", q=>"q", r=>"r", s=>"s", t=>"t", u=>"u", v=>"v", w=>"w", x=>"x", y=>"y", z=>"z"));	
	}*/
	
	public static function hashToList(hash:Hash<String>, ?startCounter:Int=0):List<Dynamic>
	{
		var data:List<Dynamic> = new List();
		
		for (key in hash.keys()) 
		{
			data.add( { key:key, value:hash.get(key) } );
		}
		return data;
	}
	
	public static function arrayToList(array:Array<String>, ?startCounter:Int=0):List<Dynamic>
	{
		var data:List<Dynamic> = new List();
		
		var c = startCounter;
		for (v in array) 
		{
			data.add( { key:c, value:v } );
			c++;
		}
		return data;
	}
	
	public static function flatArraytoList(array:Array<String>):List<Dynamic>
	{
		var data:List<Dynamic> = new List();
		
		for (i in array) data.add( { key:i, value:i } );
		
		return data;
	}
	
}