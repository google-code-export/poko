/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;

import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;


class Input extends FormElement
{
	public var password:Bool;
	public var width:Int;
	
	public function new(name:String, label:String, ?value:String, ?required:Bool=false, ?validators:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.attributes = attibutes;
		this.password = false;
		
		width = 180;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		var tType:String = password ? "password" : "text";
		
		return "<input style=\"width:"+width+"px\" type=\""+tType+"\" name=\""+n+"\" id=\""+n+"\" value=\"" +value+ "\" />"+ (if(required && form.isSubmitted()) " required");
	}
	
	public function toString() :String
	{
		return render();
	}
}