/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;

import poko.form.elements.Input;
import poko.form.Form;
import poko.form.Validator;
import poko.form.validators.BoolValidator;

class TextArea extends Input
{
	public var height:Int;

	
	public function new(name:String, label:String, ?value:String, ?required:Bool=false, ?validators:Array<Validator>, ?attibutes:String="") 
	{
		super(name, label, value, required, validators, attributes);
		
		width = 300;
		height = 50;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		
		if (showLabelAsDefaultValue && value == label){
			addValidator(new BoolValidator(false, "Not valid"));
		}
		
		if ((value == null || value == "") && showLabelAsDefaultValue) {
			value = label;
		}		
		
		var s = "";
		if (required && form.isSubmitted() && printRequired) s += "required<br />";
		var style = useSizeValues ? "style=\"width:" + width + "px; height:" + height + "px;\"" : "";
		
		s += "<textarea "+style+" name=\"" + n + "\" id=\"" + n + "\">" + value + "</textarea>";
		return s;
	}
	
	override public function toString() :String
	{
		return render();
	}	
	
}