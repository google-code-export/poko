/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.examples;

import poko.controllers.Controller;

class SuperSimple extends Controller
{
	public var myString:String;
	
	public function new() 
	{
		super();
		myString = "Hello World";
	}
	
	public function myStringBold()
	{
		return "<b>" + myString + "</b>";
	}
	
	override public function init()
	{
		super.init();
		trace(here);
	}
	
	override public function main()
	{
		super.main();
		trace(here);
	}
	
	override public function post()
	{
		super.post();
		trace(here);
	}
}