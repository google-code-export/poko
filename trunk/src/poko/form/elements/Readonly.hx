/** 
 * f'work haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;

import poko.form.Form;
import poko.form.FormElement;

class Readonly extends FormElement
{
	public var display:Bool;
	
	public function new(name:String, label:String, ?value:String, ?required:Bool = false, ?display:Bool = false,  ?attributes:String = "") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.display = display;
		this.attributes = attributes;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		
		var str:StringBuf = new StringBuf();
		
		str.add("<input type=\"hidden\" name=\"" + n + "\" id=\"" + n + "\" value=\"" +value + "\"/>");
		str.add(value);
		
		return str.toString();
	}
	
	public function toString() :String
	{
		return render();
	}
}