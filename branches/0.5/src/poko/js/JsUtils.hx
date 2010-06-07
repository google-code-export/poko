/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package poko.js;

class JsUtils 
{

	public function new() 
	{
		
	}
	
	public static function prompt( v : Dynamic ):String {
		return untyped __js__("prompt")(js.Boot.__string_rec(v,""));
	}
	
}