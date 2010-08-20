/**
 * ...
 * @author Matt Benton
 */

package poko.utils;

import php.FileSystem;
import php.io.File;

using StringTools;

class BasicTemplate 
{
	public var filePath : String;
	public var source : String;
	
	public var data : Hash<Dynamic>;
	
	public var rootTemplatePath : String;
	
	public var htmlOutput : Bool;
	
	// Private constructor
	function new()
	{
		filePath = null;
		source = null;
		
		data = new Hash<Dynamic>();
		
		rootTemplatePath = "";
		htmlOutput = false;
	}
	
	public static function fromFile( filePath : String, ?rootPath : String = null ) : BasicTemplate
	{
		var tpl = new BasicTemplate();
		
		tpl.filePath = filePath;
		
		if ( rootPath != null )
			tpl.rootTemplatePath = rootPath;	
			
		return tpl;
	}
	
	public static function fromSource( source : String ) : BasicTemplate
	{
		var tpl = new BasicTemplate();
		tpl.source = source;
		return tpl;
	}
	
	public function assignByReflection( d : Dynamic, ?overwrite : Bool = true ) : Void
	{
		for ( prop in Reflect.fields(d) )
		{
			if ( !data.exists(prop) || overwrite )
				data.set(prop, Reflect.field(d, prop));
		}
	}
	
	public function compile() : String
	{
		if ( filePath != null && FileSystem.exists(rootTemplatePath + filePath) )
			source = File.getContent(rootTemplatePath + filePath);
		
		if ( source == null )
			return null;
			
		// Include any template matches {include:template_file_path.ext}
		var regExp = ~/{include:([\w\.\/]+)}/g;
		source = regExp.customReplace(source, replaceIncludes);
		
		// Replace all variable matches {var:property}
		// 		{var:property.sub_property}
		regExp = ~/{var:([\w\.]+)}/g;
		source = regExp.customReplace(source, replaceVars);
		
		// Replace all variable matches without explicit "ver:" prefix {property}
		regExp = ~/{([\w\.]+)}/g;
		source = regExp.customReplace(source, replaceVars);
			
		return source;
	}
	
	public function compileFile( filePath : String, ?htmlOutput : Null<Bool> = null ) : String
	{
		if ( htmlOutput != null )
			this.htmlOutput = htmlOutput;
		
		this.filePath = filePath;
		
		return compile();
	}
	
	function replaceIncludes( regExp : EReg ) : String
	{
		var tplPath = regExp.matched(1);
		if ( tplPath != null && FileSystem.exists(rootTemplatePath + tplPath) )
		{
			var tpl = BasicTemplate.fromFile(tplPath);
			tpl.data = data;
			tpl.rootTemplatePath = rootTemplatePath;
			return tpl.compile();
		}
		return "#include:" + tplPath + "#";
	}
	
	function replaceVars( regExp : EReg ) : String
	{
		var propStr = regExp.matched(1);
		
		var segments = propStr.split(".");
		if ( segments.length > 1 )
		{
			var first = segments.shift();
			var value : Dynamic = data.exists(first) ? data.get(first) : null;
			while ( value != null )
			{
				var prop = segments.shift();
				value = Reflect.hasField(value, prop) ? Reflect.field(value, prop) : null;
				if ( segments.length == 0 )
					break;
			}
			return (value != null) ? prepVar(value) : "#var:" + propStr + "#";
		}
		else
		{
			return data.exists(propStr) ? prepVar(data.get(propStr)) : "#var:" + propStr + "#";
		}
		
		//return "";
		//return data.exists(prop) ? data.get(prop) : "#var:"+prop+"#";
	}
	
	inline function prepVar( value : String ) : String
	{
		if ( htmlOutput )
		{
			return value.replace( "\n", "<br/>" );
		}
		else
		{
			return value;
		}
	}
}