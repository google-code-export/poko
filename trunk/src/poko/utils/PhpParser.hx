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
