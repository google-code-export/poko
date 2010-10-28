package poko;

import php.FileSystem;
import poko.Application;

class Component extends ViewContext
{
	public var output:String;
	
	public function new() 
	{
		super();
		
		template_file = findTemplate(Type.getClassName(Type.getClass(this)));
		
		application = Application.instance;
		application.components.add(this);
	}
	
	private function findTemplate(name:String)
	{
		var n = StringTools.replace(name, ".", "__");
		if (FileSystem.exists("tpl/" + n + ".mtt.php")) return n + ".mtt";
		if (FileSystem.exists("tpl/" + n + ".php")) return n + ".php";
		return "";
	}
	
	public function setTemplate(file:String):Void
	{
		template_file = Application.instance.packageRoot + "/" + file;
	}
	
	public function pre(){}
	public function main(){}
	
	public function setOutput(value)
	{
		output = Std.string(value);
	}
	
	override public function render():String
	{
		if (output != null)
		{
			return output;
		} else {
			
		  return  super.render();
		}
	}
	
	override public function toString()
	{
		return "NAAAV";
	}
}