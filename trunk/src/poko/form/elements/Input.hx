/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;

import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;
import poko.form.validators.BoolValidator;


class Input extends FormElement
{
	public var password:Bool;
	public var width:Int;
	public var showLabelAsDefaultValue:Bool;
	public var useSizeValues:Bool;
	public var printRequired:Bool;
	
	public function new(name:String, label:String, ?value:String, ?required:Bool=false, ?validators:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.attributes = attibutes;
		this.password = false;
		
		showLabelAsDefaultValue = false;
		useSizeValues = false;
		printRequired = true;
		
		width = 180;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		var tType:String = password ? "password" : "text";
		
		if (showLabelAsDefaultValue && value == label){
			addValidator(new BoolValidator(false, "Not valid"));
		}
		
		if ((value == null || value == "") && showLabelAsDefaultValue) {
			value = label;
		}		
		
		var style = useSizeValues ? "style=\"width:"+width+"px\"" : "";
		return "<input "+style+" type=\""+tType+"\" name=\""+n+"\" id=\""+n+"\" value=\"" +value+ "\" />" + (if(required && form.isSubmitted() && printRequired) " required");
	}
	
	public function toString() :String
	{
		return render();
	}
}