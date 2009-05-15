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

package site.services;

import poko.utils.ImageProcessor;
import poko.utils.PhpTools;
import php.FileSystem;
import php.io.File;
import php.io.Process;
import php.Lib;
import php.Sys;
import php.Web;
import poko.Request;

class Image extends Request
{
	public var data:Dynamic;
	
	public function new() 
	{
		super();
	}
	
	override public function main():Void
	{
		var src:String = application.params.get("src");
		
		if (application.params.get("preset") != null)
		{
			var image:ImageProcessor = new ImageProcessor(application.uploadFolder + "/" + src);
			image.cacheFolder = application.uploadFolder+ "/cache";
			image.format = ImageOutputFormat.JPG;		
			
			
			switch(application.params.get("preset"))
			{
				case "tiny":
					image.queueFitSize(40, 40);
				case "thumb":
					image.queueFitSize(100, 100);
				case "square":
					image.queueFitSize(200, 200);
				case "aspect": 
					var w:Int = Std.parseInt(application.params.get("w"));
					var h:Int = Std.parseInt(application.params.get("h"));
					image.queueCropToAspect(w, h);
				case "custom": 
					var w:Int = Std.parseInt(application.params.get("w"));
					var h:Int = Std.parseInt(application.params.get("h"));
					image.queueFitSize(w, h);
			}
			
			image.flushOutput();
			
		}else {
			Web.setHeader("content-type", "image");
			
			Web.setHeader("Expires", "");
			Web.setHeader("Cache-Control", "");
			Web.setHeader("Pragma", "");
			
			Web.setHeader("Content-Disposition", "inline; filename=" + src.substr(32));
			Web.setHeader("Etag", src);
			Web.setHeader("Content-Transfer-Encoding", "binary");
			
			#if php
				Web.setHeader("Content-Length", untyped __call__("filesize", application.uploadFolder + "/" + src));
				untyped __call__("readfile", application.uploadFolder + "/" + src);
				Sys.exit(1);
			#else
				var f = File.getContent(application.uploadFolder + "/" + src);
				//Web.setHeader("Content-Length", f.length);
				
				setOutput(f);
			#end
		}
	}
}