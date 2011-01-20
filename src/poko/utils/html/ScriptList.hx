/**
 * ...
 * @author Matt Benton
 */

package poko.utils.html;

import php.FileSystem;
import poko.controllers.HtmlController;
import poko.Poko;

class ScriptList 
{
	public var scripts : Array<ScriptRef>;
	
	public function new() 
	{
		scripts = new Array<ScriptRef>();
	}
	
	//public function addExternal( type : ScriptType, url : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	public function addExternal( type : ScriptType, url : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	{
		//var script = { type: type, isExternal: true, value: url, condition: condition, media: media, priority: priority };
		//if ( !Lambda.has(scripts, script, compareScriptRef) )
			//scripts.push(script);
		scripts.push( { type: type, isExternal: true, value: url, condition: condition, media: media, priority: priority } );
	}
	
	public function addExternalJS( url : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	{
		scripts.push( { type: ScriptType.js, isExternal: true, value: url, condition: condition, media: media, priority: priority } );
	}
	public function addExternalCSS( url : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	{
		scripts.push( { type: ScriptType.css, isExternal: true, value: url, condition: condition, media: media, priority: priority } );
	}
	
	
	//public function addInline( type : ScriptType, source : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	public function addInline( type : ScriptType, source : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	{
		scripts.push( { type: type, isExternal: false, value: source, condition: condition, media: media, priority: priority } );
	}
	public function addInlineJS( type : ScriptType, source : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	{
		scripts.push( { type: ScriptType.js, isExternal: false, value: source, condition: condition, media: media, priority: priority } );
	}
	public function addInlineCSS( type : ScriptType, source : String, ?condition : String = null, ?media : String = null, ?priority : Int = 0 ) : Void
	{
		scripts.push( { type: ScriptType.css, isExternal: false, value: source, condition: condition, media: media, priority: priority } );
	}
	
	//function compareScriptRef( a:ScriptRef, b:ScriptRef ) : Bool
	//{
		//return ( a.type == b.type && a.isExternal == b.isExternal && a.value == b.value );
	//}
	
	public function getScripts() : String
	{
		var output = "";
		
		#if debug
		var missing = new Array<String>();
		#end
		
		//scripts.sort(sortScripts);
		
		for ( script in scripts )
		{
			#if debug
			if ( script.isExternal )
				if ( !FileSystem.exists(script.value) )
					missing.push(script.value);
			#end
			
			output += ( script.isExternal ) ? formatExternalScript( script ) + "\n" : formatInlineScript( script ) + "\n";
		}
		
		// special binds that are allowed in poko
		if (Std.is(Poko.instance.controller, HtmlController))
		{
			var jsBindings = cast(Poko.instance.controller, HtmlController).jsBindings;
			for(jsBinding in jsBindings.keys())
				output += "<script> poko.js.JsPoko.instance.addRequest(\"" + jsBinding + "\") </script> \n";			
		}
		
		#if debug
		if ( missing.length > 0 )
		{
			var error = "Warning: (" + missing.length + ") external script(s) could not be found:\n";
			for ( s in missing )
				error += "\t" + s + "\n";
			throw error;
		}
		#end
		
		return output;
	}
	
	function sortScripts( a:ScriptRef, b:ScriptRef ) : Int
	{
		return (b.priority - a.priority);
	}
	
	function formatInlineScript( script : ScriptRef ) : String
	{
		var output : String = "";
		switch ( script.type )
		{
			case ScriptType.css:
				output = (script.media == null) ? "<style type=\"text/css\">" + script.value + "</style>" : "<style type=\"text/css\" media=\"" + script.media + "\">" + script.value + "</style>";
			case ScriptType.js:
				output = "<script>" + script.value + "</script>";
		}
		if ( script.condition != null )
			return formatCondition( output, script.condition );
		return output;
	}
	
	function formatExternalScript( script : ScriptRef ) : String
	{
		var output : String = "";
		switch ( script.type )
		{
			case ScriptType.css:
					output = (script.media == null) ? "<link href=\"" + script.value + "\" rel=\"stylesheet\" type=\"text/css\" />" : "<link href=\"" + script.value + "\" rel=\"stylesheet\" type=\"text/css\" media=\"" + script.media + "\" />";
			case ScriptType.js:
				output = '<script type="text/javascript" src="' + script.value + '"></script>';
		}
		if ( script.condition != null )
			return formatCondition( output, script.condition );
		return output;
	}
	
	function formatCondition( value : String, condition : String ) : String
	{
		return "<!--[" + condition + "]>" + value + "<![endif]-->";
	}
}