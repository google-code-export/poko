/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.utils;

class StringTools2
{

	public function new() 
	{
		
	}
	
	public static function ucFirst(s:String) 
	{
		var p1:String = s.substr(0, 1);
		var p2:String = s.substr(1);
		return p1.toUpperCase() + p2.toLowerCase();
	}
	
	/** takes string with %s identifiers and impodes array values */
	public static function printf(string:String, vars:Array<Dynamic>)
	{
		var parts = string.split("%s");
		var out = "";
		var c = 0;
		var lastpart = parts.pop();
		for (part in parts)
		{
			if (c < vars.length)
			{
				out += part + vars[c];
			} else {
				out += part;
			}
			c++;
		}
		out += lastpart;
		
		return out;
	}
	
	public static function toSentenceList(input:String, splitBy:String = "", surroundBy:String = "'"):String
	{
		var a = input.split(splitBy);
		var c = 0;
		var out = "";
		for (s in a) {
			out += surroundBy + s + surroundBy;
			if (c < a.length - 2) {
				out += ", ";
			}else if(c == a.length - 2) {
				out += " and ";
			}
			c++;
		}
		
		return out;
	}
}