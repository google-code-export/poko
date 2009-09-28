package poko;

import poko.Application;

class Component extends TemploContext
{
	public var output:String;
	
	public function new() 
	{
		super();
		template_file = StringTools.replace(Type.getClassName(Type.getClass(this)), ".", "/") + ".mtt";
		
		application = Application.instance;
		application.components.add(this);
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
		return output != null ? output : super.render();
	}
	
	override public function toString()
	{
		return "NAAAV";
	}
}