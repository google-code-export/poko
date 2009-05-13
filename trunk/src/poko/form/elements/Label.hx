/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form;

class Label 
{
	public var forElement:FormElement;
	public var value:String;
	
	public function new() 
	{
		
	}
	
	override public function render():String
	{
		
	}
	
	public function populate(data:String) 
	{
		value = data;
	}
}