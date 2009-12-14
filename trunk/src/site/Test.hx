/**
 * ...
 * @author Tony Polinelli
 */

package site;
import site.components.TestComponent;
import site.layouts.TestLayout;

class Test extends TestLayout
{
	public var test:String;
	
	public var comp:TestComponent;
	public var poo:String;
	
	override public function init ()
	{
		poo = "sss";
		
		test = "mmm";
		
		comp = new TestComponent("test comp");
	}
	
	override public function post()
	{
	}
	
}