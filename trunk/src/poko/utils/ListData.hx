/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
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
	
	public static function getMonths(?short = false)
	{
		var data:List<Dynamic> = new List();
		
		if(short){
			data = arrayToList(["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], 1);
		} else {
			data = arrayToList(["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], 1);
		}
		return(data);
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
		for (i in array) 
		{
			data.add( { key:i, value:c } );
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