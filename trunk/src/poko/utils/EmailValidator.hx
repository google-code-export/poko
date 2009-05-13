/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

 package com.touchmypixel.utils;

class EmailValidator
{
	
	
	public static function isValidEmail(email : String) : Bool{
		return EMAIL_REGEX.match(email);
	}
	
	public static var EMAIL_REGEX : EReg = ~/^[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
	
}
