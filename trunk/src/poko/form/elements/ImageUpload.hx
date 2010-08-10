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


package poko.form.elements;
import haxe.Md5;
import haxe.Timer;
import php.FileSystem;
import poko.Poko;
import poko.form.Form;
import poko.form.FormElement;
import poko.utils.PhpTools;

class ImageUpload extends FormElement
{
	public var imageServiceUrl:String;
	public var imageServicePreset:String;
	public var tempFolder:String;
	
	public function new(name:String, label:String, ?value:String, ?required:Bool=false, ?imageServiceUrl:String=null, ?imageServicePreset=null, ?tempFolder:String=null ) 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.imageServiceUrl = imageServiceUrl != null ? imageServiceUrl : "?request=services.Image";
		this.imageServicePreset = imageServicePreset != null ? imageServicePreset : "thumb";
		this.tempFolder = tempFolder != null ? tempFolder : "temp/";
	}
	
	override public function populate()
	{
		var n = form.name + "_" + name;
		var previous = Poko.instance.params.get(n+"__previous");
		var file:Hash<String> = PhpTools.getFilesInfo().get(n);
		
		if (file != null &&file.get("error") == "0")
		{
			if (FileSystem.exists(file.get("tmp_name")))
			{
				var newpath = Poko.instance.config.applicationPath + "res/" + tempFolder + Md5.encode(Timer.stamp()+file.get("name")) + file.get("name");
				PhpTools.moveFile(file.get("tmp_name"), newpath);
				value = newpath;
			}
			
		} else if(previous != null) {
			value = previous;
		}
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		var str:String = "";
		
		str += '<img src="'+imageServiceUrl+'&preset='+imageServicePreset+'&src='+tempFolder+getPathFileName()+'" /><br/>';
		str += '<input type="file" name="' + n + '" id="' + n + '" ' + attributes + ' />';
		str += '<input type="hidden" name="' + n + '__previous" id="' + n + '__previous" value="'+value+'"/>';
		
		return str;
	}
	
	public function getPathFileName()
	{
		var s = Std.string(value);
		return s.substr(s.lastIndexOf("/") + 1);
	}
	
	public function getFileName()
	{
		var s = Std.string(value);
		return s.substr(s.lastIndexOf("/") + 33);
	}
	
	public function toString() :String
	{
		return render();
	}
	
}