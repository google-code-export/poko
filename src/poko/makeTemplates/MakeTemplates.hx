/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tony Polinelli <tonyp@touchmypixel.com> 
 * Contributers: Tarwin Stroh-Spijer 
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   - Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   - Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE TOUCH MY PIXEL & CONTRIBUTERS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE TOUCH MY PIXEL & CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 */

package;

import neko.FileSystem;
import neko.Lib;
import neko.Sys;


class Test {
	
	public function new() {}
	
	public function toString()
	{
		return "toString on Test1";
	}
}

class Test2 extends Test
{
	public function new()
	{
		super();
	}
}


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
	var items:Array<{file:String, type:TemplateType}>;
	
	static function main() 
	{
		new MakeTemplates();
	}
	
	public function new()
	{
		//return;
		
		parseArgs();
		
		items = [];
		
		if (!FileSystem.exists(to))
			FileSystem.createDirectory(to);
				
		recurse(from);
		
		var tempoArgs = ["-output", to, "-php"];
		
		var c = 0;
		for (item in items)
		{
			switch(item.type)
			{
				case TemplateType.TEMPLO:  tempoArgs.push(item.file);
				case TemplateType.PHP:  copyFile(item.file);
			}
			c++;
			if (c == 5) break;
		}
		
		neko.Sys.command("temploc2.exe", tempoArgs); 
		
		//if(removePath != null) cleanUp();
	}
	
	private function copyFile(file:String):Void
	{
		var name = StringTools.replace(file, "\\", "__" );
		neko.io.File.copy(file, to + "/" + name);
	}
	
	private function cleanUp():Void
	{
		var dir = FileSystem.readDirectory(to);
		removePath = StringTools.replace(removePath, "/", "\\");
		var prefix = StringTools.replace(removePath, "\\", "__" ) + "__";
		
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
			if (FileSystem.isDirectory(path + "/" + item))
			{
				recurse(path + "/" + item);
			}
			else
			{	
				switch(item.substr(item.lastIndexOf(".") + 1).toLowerCase())
				{
					case "mtt": items.push({file:path + "/" + item, type:TEMPLO});
					case "php": items.push({file:path + "/" + item, type:PHP});
				}
			}
		}
	}
	
}

enum TemplateType
{
	TEMPLO;
	PHP;
}