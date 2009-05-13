
package;
import neko.FileSystem;
import neko.Lib;
import neko.Sys;

/**
 * Simple Program which iterates -from folder, finds .mtt templates and compiles them to the -to folder
 */
class MakeTemplates
{
	public static inline var keys = ["-from", "-to", "-removePath"];
	
	var to:String;
	var from:String;
	var removePath:String;
	var cp:String;
	
	var sysargs:Array<String>;
	var items:Array<String>;
	
	static function main() 
	{
		new MakeTemplates();
	}
	
	public function new()
	{
		parseArgs();
		
		items = [];
		
		if (!FileSystem.exists(to))
			FileSystem.createDirectory(to);
				
		recurse(from);
		
		var s = "temploc2.exe";
		var cargs = ["-output", to, "-php"];
		cargs = cargs.concat(items);

		neko.Sys.command(s, cargs); 
		
		if(removePath != null) cleanUp();
	}
	
	private function cleanUp():Void
	{
		var dir = FileSystem.readDirectory(to);
		var prefix = StringTools.replace(removePath, "\\", "__" )+"__";
		for (item in dir)
		{
			var name = item.substr(prefix.length);
			
			if (FileSystem.exists(to + "\\" + name)) 
				FileSystem.deleteFile(to + "\\" + name);
			if (item.indexOf(prefix) == 0) 
				FileSystem.rename(to + "\\" + item, to + "\\" + item.substr(prefix.length));
		}
	}
	
	private function parseArgs():Void
	{
		// Parse args
		var args = Sys.args();
		for (i in 0...args.length)
			if (Lambda.has(keys, args[i]))
				Reflect.setField(this, args[i].substr(1), args[i + 1]);
			
		// Check to see if argument is missing
		if (to == null) { Lib.println("Missing argument '-to'"); return; }
		if (from == null) { Lib.println("Missing argument '-from'"); return; }
	}
	
	public function recurse(path:String)
	{
		var dir = FileSystem.readDirectory(path);
		
		for (item in dir)
		{
			if (FileSystem.isDirectory(path + "\\" + item))
				recurse(path + "\\" + item);
			else 
				if (item.substr(item.lastIndexOf(".") + 1) == "mtt")
					addTemplate(path + "\\" + item);
		}
	}
	
	public function addTemplate(item:String)
	{
		items.push(item);
	}
}