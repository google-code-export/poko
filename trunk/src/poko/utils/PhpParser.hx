/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.utils;

import poko.utils.PhpTools;
import php.Lib;
import php.NativeArray;
import php.Sys;

class PhpParser implements Dynamic, implements PhpParserRenderable
{
	private var template_file:String;
	private var template_rendered:Bool;
	private var template_data:Dynamic;
	
	public static function __init__():Void
	{
		PhpParserGlobalFunctions.main();
    }
	
	public function new(?template:String, ?data:Dynamic) 
	{
		template_rendered = false;
		for (field in Reflect.fields(data)) Reflect.setField(this, field, Reflect.field(data, field));
		template_file = (template != null) ? template : "";
	}
	
	public function toString():String
	{
		trace("[PhpParser " + template_file + "]");
		PhpTools.pf(this);
		return "";
	}
	
	public function render():String
	{			
		if (template_rendered) return "";
		template_rendered = true;
		template_prepareData();
		return template_parseTemplate(template_file);
	}
	
	/* use php's output buffer and extract to parse a template */
	public function template_parseTemplate(template)
	{
		untyped __call__("ob_start");
		untyped __call__("extract", __php__("(array)$this->template_data"));
		untyped __call__("include", template);
		var out = untyped __call__("ob_get_contents");
		untyped __call__("ob_end_clean");
		return out;
	}
	
	public function template_prepareData()
	{
		template_data = { };
		
		// prepare dynamic properties (send in from constructor)
		var k = "";
		var v:Dynamic = null;
		untyped __php__("foreach($this->__dynamics as $k=> $v){ ");		
				
			if (Std.is(v, PhpParserRenderable)) 
			{
				Reflect.setField(template_data, k, v.render());
			} else {
				Reflect.setField(template_data, k, v);
			}
		untyped __php__("}");
		
		// prepare class properties
		for (i in Reflect.fields(this))
		{
			if (Std.is(Reflect.field(this, i), PhpParserRenderable))
				Reflect.setField(template_data, i, Reflect.field(this, i).render());
			else
				Reflect.setField(template_data, i, Reflect.field(this, i));
		}
	}
	
	public static function template_parse(file:String, ?data:Dynamic):String
	{
		var tpl:PhpParser = new PhpParser(file, data);
		return tpl.render();
	}
}

/**  These are funtions used in the php templates
 * }} and {{ are used to break out of the class and add function to the global function space 
 * */

class PhpParserGlobalFunctions {
	
	public static function main():Void
	{
		untyped __php__("}} 

			/**  iterate over the haXe iteratable objects */
			function iterate($a) 
			{ 
				if (is_a($a, '_hx_array'))
				{
					return $a->__a; 
				} 
				else if (is_a($a, 'Hash'))
				{
					return $a->h; 
				} 
				else if (is_a($a, 'HList'))
				{
					$arr = array();
					$__it__ = $a->iterator();
					while ($__it__ -> hasNext()) 
					{
						$i = $__it__->next();
						array_push($arr, $i);
					}
					return $arr; 
				} 
				else 
				{
					return $a;	
				}
			} 			
			
			function countOf($a)
			{ 
				if (is_a($a, '_hx_array'))
				{
					return count($a->__a); 
				} 
				else if (is_a($a, 'Hash'))
				{
					return count($a->h); 
				} 
				else if (is_a($a, 'HList'))
				{
					return $a->length;
				} 
				else 
				{
					return $a;
				}
			} 			
			
			/**  call a closure */
			function call($arr)
			{
				$args = func_get_args();
				array_shift($args);
				
				return call_user_func_array($arr, $args);
			}
			
			/**  get the poko application */
			function getApplication()
			{
				return poko_Application::$instance;
			}
			
			/**  include the request content in the main template */
			function includeRequestContent()
			{
				echo getApplication()->request->request_includeContent();
			}
			
			function pf($v) { echo \"<pre>\" ; print_r($v); echo \"</pre>\" ; }
			
		{{");
	}
}

interface PhpParserRenderable 
{
	function render():String;
}
