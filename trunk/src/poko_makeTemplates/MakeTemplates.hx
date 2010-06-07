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

using StringTools;

/**
 * Simple Program which iterates -from folder, finds .mtt templates and compiles them to the -to folder
 */
class MakeTemplates
{
	public static inline var keys = ["-from", "-to", "-remove"];
	
	var to:String;
	var from:String;
	var remove:String;
	var cp:String;
	
	var sysargs:Array<String>;
	var items:Array<String>;
	
	static function main() 
	{
		new MakeTemplates();
	}
	
	public function new()
	{
		//return;
		
		parseArgs();
		
		if (!FileSystem.exists(to)) FileSystem.createDirectory(to);
		if (!FileSystem.exists(to + "/php")) FileSystem.createDirectory(to+ "/php");
		if (!FileSystem.exists(to + "/mtt")) FileSystem.createDirectory(to+ "/mtt");
		
		items = [];
		
		recurse(from);
		
		var tempoArgs = ["-output", to + "/mtt", "-php"];
		
		
		for (item in items)
		{
			var ext = getExt(item);
			switch(ext)
			{
				case "mtt": tempoArgs.push(item);
				case "php", "ht": copyPhpFile(item,ext);
			}
		}
		
		
		neko.Sys.command("temploc2.exe", tempoArgs); 
		
		cleanUp();
	}
	
	private function copyPhpFile(file, ext):Void
	{
		var fromFile = file;
		var toFile = to + "/"+ext+"/" + clean(file);
		var dir = toFile.substr(0, toFile.lastIndexOf("/"));
		
		createFolder(dir);
		
		neko.io.File.copy(fromFile, toFile);
	}
	
	private function createFolder(path:String):Void
	{
		var parts = path.split("/");
		var folder = ".";
		for (part in parts)
		{
			folder += "/" + part;
			if (!FileSystem.exists(folder)) FileSystem.createDirectory(folder);
		}
	}
	
	private function cleanUp():Void
	{
		var dir = FileSystem.readDirectory(to + "/mtt");

		for (item in dir)
		{
			if (item.lastIndexOf("mtt.php") != -1)
			{
				var fromFile = to + "/mtt/" + item;
				var toFile = to + "/mtt/" + clean(item, true);
				
				if (fromFile != toFile)
				{
					if (FileSystem.exists(toFile))
						FileSystem.deleteFile(toFile);
					
					FileSystem.rename(fromFile, toFile);
				}
			}
		}
	}
	
	public function clean(item:String, ?underscore:Bool=false)
	{
		var r = from + "/";
		
		if (remove != null) r += remove + "/";
		
		if (underscore) r = r.replace("/", "__");
		
		if (item.startsWith(r)) item = item.substr(r.length);
		
		return item; 
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
			var s = path + "/" + item;
			if (FileSystem.isDirectory(s))
			{
				recurse(s);
			}
			else
			{
				var exts = ["ht", "php", "mtt"];
				if(Lambda.has(exts, getExt(item)))
					items.push(s);
			}
		}
	}
	
	public function getExt(s:String)
	{
		return s.substr(s.lastIndexOf(".") + 1).toLowerCase();
	}
	
}

enum TemplateType
{
	TEMPLO;
	PHP;
}