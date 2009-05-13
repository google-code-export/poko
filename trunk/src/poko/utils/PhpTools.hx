/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.utils;

import php.Lib;
import php.NativeArray;
import php.Sys;
import php.Web;

class PhpTools
{
	
	public static function pf(obj:Dynamic):Void
	{
		Lib.print("<pre>");
		untyped __call__("print_r", obj);
		Lib.print("</pre>");
	}
	
	public static function setupTrace()
	{
		var f = haxe.Log.trace;
		
		haxe.Log.trace = function(v, ?pos) 
		{ 
			f(v, pos);
			
			Lib.print("<br />");
			//Lib.print("<script> console.log(\""+v+"\") </script>");
		}
		
	}
	
	public static function mail(to:String, subject:String, message, ?headers:String, ?additionalParameters:String):Void
	{
		untyped __call__("mail", to, subject, message, headers, additionalParameters);	
	}
	
	public static function moveFile(filename:String, destination):Void
	{
		var success = untyped __call__("move_uploaded_file",  filename, destination);
		
		if (!success)
			throw "Error uploading '" + filename + "' to '" + destination + "'";
	}
	
	public static function getFilesInfo():Hash <Hash<String>>
	{
		var files:Hash<NativeArray> = php.Lib.hashOfAssociativeArray(untyped __php__("$_FILES"));
		var output:Hash < Hash < String >> = new Hash();
		
		for (file in files.keys())
			output.set(file, php.Lib.hashOfAssociativeArray(files.get(file)));	
		
		return output;
	}
	
	
	
}