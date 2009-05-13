/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko;

import poko.Application;

class Component extends TemploObject
{
	public var output:String;
	
	public function new() 
	{
		super(StringTools.replace(Type.getClassName(Type.getClass(this)), ".", "/") + ".mtt");
		
		application = Application.instance;
	}
	
	public function setTemplate(file:String):Void
	{
		template_file = Application.instance.packageRoot + "/" + file;
	}
	
	public function main(){}
	
	public function setOutput(value)
	{
		output = Std.string(value);
	}
	
	override public function render():String
	{
		main();
		
		return output != null ? output : super.render();
	}
	
}