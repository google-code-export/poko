/**
 * ...
 * @author Matt Benton
 */

package poko.utils.mailtest;
import php.FileSystem;
import php.io.File;
import php.Web;

class Part 
{
	public var content : String;
	public var contentID : String;
	public var contentType : String;
	public var contentEncoding: String;
	public var contentDisposition: String;
	public var contentName : String;
	
	public function new() { }
	
	public static function image( url : String, cid: String, ?type : String = null ) : Part
	{
		var part = new Part();
		part.content = untyped __call__("chunk_split", __call__("base64_encode", File.getContent(url)));
		part.contentID = cid;
		part.contentType = "image/" + type;
		part.contentEncoding = "base64";
		return part;
	}
	
	public static function html( content : String ) : Part
	{
		var part = new Part();
		part.content = content;
		part.contentType = "text/html";
		return part;
	}
	
	public static function attachment( url : String, name : String, ?type : String = null ) : Part
	{
		var part = new Part();
		part.content = untyped __call__("chunk_split", __call__("base64_encode", File.getContent(url)));
		part.contentType = "attachment/" + type;
		part.contentName = name;
		part.contentEncoding = "base64";
		part.contentDisposition = "attachment";
		return part;
	}
}