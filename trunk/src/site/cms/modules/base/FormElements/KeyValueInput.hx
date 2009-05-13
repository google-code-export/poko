/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base.formElements;

import poko.Application;
import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;
import poko.utils.JsBinding;
import site.cms.common.InputTypeDef;
import haxe.Serializer;

class KeyValueInput extends FormElement
{
	public var properties:Dynamic;
	private var jsBind:JsBinding;
	
	public function new(name:String, label:String, ?value:String, ?properties:Dynamic, ?validatorsKey:Array<Validator>, ?validatorsValue:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.attributes = attibutes;
		this.properties = properties;
		
		jsBind = new JsBinding("site.cms.modules.base.js.JsKeyValueInput");
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		var keyLabel = properties.keyLabel == "" ? "Key" : properties.keyLabel;
		var valueLabel = properties.valueLabel == "" ? "Value" : properties.valueLabel;
		
		var keyType:InputTypeDef = { isMultiline:properties.keyIsMultiline == "1", width:Std.parseInt(properties.keyWidth), height:Std.parseInt(properties.keyHeight) };
		var valueType:InputTypeDef = { isMultiline:properties.valueIsMultiline == "1", width:Std.parseInt(properties.valueWidth), height:Std.parseInt(properties.valueHeight) };
		
		var s = "<input type=\"hidden\" name=\"" + n + "\" id=\"" + n + "\" value=\"" +value + "\" />";
		s += "<table id=\""+n+"_keyValueTable\">";
		s += "	<tr><td><label>"+keyLabel+"</label></td><td><label>"+valueLabel+"</label></td><td></td></tr>";
		s += "</table>";
		s += "<div><a href=\"#\" onClick=\"" + jsBind.getCall("addKeyValueInput", []) + "; return(false);\">add row</a></div>";
		s += "<script>" + jsBind.getCall("setupKeyValueInput", [n, properties]) + "</script>"; 
		return s;
	}
	
	public function toString():String
	{
		return render();
	}
}