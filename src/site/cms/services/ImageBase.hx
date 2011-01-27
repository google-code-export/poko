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

package site.cms.services;

import haxe.Md5;
import poko.utils.ImageProcessor;
import poko.utils.PhpTools;
import php.FileSystem;
import php.io.File;
import php.io.Process;
import php.Lib;
import php.Sys;
import php.Web;
import poko.controllers.HtmlController;

class ImageBase extends poko.controllers.Controller
{
	public var data:Dynamic;
	
	public function new() 
	{
		super();
	}
	
	override public function main():Void
	{
		var src:String = app.params.get("src");
		
		// Just in case we're asked for a blank source don't send back an HTML error.
		if (src == "" || src == null) {
			
			// base64 encoded 1x1 pixel GIF
			var blankGif = "R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
			// hash for caching
			var blankHash = Md5.encode(blankGif);
			
			setHeaders( Date.fromTime(0), "43", blankHash, "image/gif" );
			Lib.print(untyped __call__("base64_decode", blankGif));
			
			return;
		}
		
		if ( app.params.exists("preset") )
		{
			// Create a new image processor.
			var image : ImageProcessor 	= new ImageProcessor( site.cms.PokoCms.uploadFolder + src );
			image.cacheFolder 			= site.cms.PokoCms.uploadFolder + "cache";
			image.format 				= ImageOutputFormat.JPG;
			//image.forceNoCache 			= true;
			
			// Resize image. Subclasses should override this method.
			resizeImage( app.params.get("preset"), image );
			
			// Get binary output.
			var imageStr = image.getOutput();
			var filename = image.cacheFolder + "/" + image.getCacheName();
			// Attempt to get the actual reported filesize on disk if available, else use the 
			// size of the string which should also return number of bytes because it is in binary format.
			var length = FileSystem.exists(filename) ? untyped __call__("filesize", filename) : Std.string(imageStr.length);
			
			// Set content headers.
			setHeaders( image.dateModified, length, image.hash, image.mimeType );
			
			// Finally output the image.
			Lib.print( imageStr );
		}
		else
		{
			var dateModified = FileSystem.stat(site.cms.PokoCms.uploadFolder + src).mtime;
			
			
			var mime = src.substr(src.lastIndexOf(".") + 1).toLowerCase();
			if (mime == "jpg") mime = "jpeg";
			mime = "image/" + mime;
			
			#if php
				var length = untyped __call__("filesize", site.cms.PokoCms.uploadFolder + src);
				// Set content headers.
				setHeaders( dateModified, length, Md5.encode(src), mime );
				// Finally output the image.
				untyped __call__("readfile", site.cms.PokoCms.uploadFolder + src);
				Sys.exit(1);
			#else
				var f = File.getContent(site.cms.PokoCms.uploadFolder + src);
				// Set content headers.
				setHeaders( dateModified, Std.string(f.length), Md5.encode(src), mime );
				// Finally output the image.
				Lib.print( f );
			#end
		}
	}
	
	function resizeImage( preset : String, image : ImageProcessor ) : Void
	{
	}
	
	function setHeaders( dateModified : Date, length : String, hash : String, mime : String ) : Void
	{
		Web.setHeader("Last-Modified", DateTools.format(dateModified, "%a, %d %b %Y %H:%M:%S") + ' GMT' );
		Web.setHeader("Expires", DateTools.format(new Date(dateModified.getFullYear() + 1, dateModified.getMonth(), dateModified.getDay(), 0, 0, 0), "%a, %d %b %Y %H:%M:%S") + ' GMT');
		Web.setHeader("Cache-Control" ,"public, max-age=31536000");
		Web.setHeader("ETag", "\"" + hash + "\"");
		Web.setHeader("Pragma", "");
		Web.setHeader("Content-Type", mime);
		Web.setHeader("Content-Length", length );
	}
}