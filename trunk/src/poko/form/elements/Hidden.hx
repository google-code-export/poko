/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;

import poko.form.Form;
import poko.form.FormElement;

class Hidden extends FormElement
{
	public var display:Bool;
	
	public function new(name:String, ?value:Dynamic, ?required:Bool = false, ?display:Bool = false,  ?attributes:String = "") 
	{
		super();
		this.name = name;
		this.value = value;
		this.required = required;
		this.display = display;
		this.attributes = attributes;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;

		return "<input type=\"hidden\" name=\""+n+"\" id=\""+n+"\" value=\"" +value+ "\"/>";
	}
	
	override public function getPreview():String
	{
		return this.render();
	}	
	
	public function toString() :String
	{
		return render();
	}
}