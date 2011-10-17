/**
 * ...
 * @author Tony Polinelli
 */

package site;
import poko.controllers.HtmlController;
import site.components.TestComponent;
import site.layouts.TestLayout;

class Test extends HtmlController
{
	public var test:String;
	
	public var comp:TestComponent;
	public var poo:String;
	private var arr:Array<String>;
	
	override public function init ()
	{
		poo = "sss";
		
		test = "mmm";
		
		arr= ["one", "two", "threree"];
		
		comp = new TestComponent("test comp");
	}
	
	override public function post()
	{
	}
	
}