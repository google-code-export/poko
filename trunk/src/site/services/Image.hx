
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
		
		
		if (application.params.get("preset"))
		{
			var image:ImageProcessor = new ImageProcessor(application.uploadFolder + "/" + src);
			image.cacheFolder = application.uploadFolder+ "/cache";
			image.format = ImageOutputFormat.PNG;
			
			switch(application.params.get("preset"))
			{
				case "tiny":
					image.queueFitSize(40, 40);
				case "thumb":
					image.queueFitSize(100, 100);
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