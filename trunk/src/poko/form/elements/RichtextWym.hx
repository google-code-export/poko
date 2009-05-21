/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;

import poko.Application;
import poko.form.Form;
import poko.form.FormElement;

class RichtextWym extends FormElement
{
	public var width:Float;
	public var height:Float;
	
	public function new(name:String, label:String, ?value:String, ?required:Bool=false, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.attributes = attibutes;
		
		width = 500;
		height = 300;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		
		var str:StringBuf = new StringBuf();
		str.add("\n <textarea name=\"" + n + "\" id=\"" + n + "\" >" + value + "</textarea>");
		str.add("<script type=\"text/javascript\">");
		str.add("jQuery(function() {");
		str.add("	jQuery('#" + n + "').wymeditor({");
		str.add("		stylesheet: './css/site.css'");
		str.add("	});");
		str.add("});");
		str.add("</script>");
		
		if (!isValid()) str.add(" required");
		
		return str.toString();
	}
	
	public function toString() :String
	{
		return render();
	}
}