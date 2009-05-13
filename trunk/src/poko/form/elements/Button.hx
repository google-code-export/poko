/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;

import poko.form.Form;
import poko.form.FormElement;

class Button extends FormElement
{
	public var type:ButtonType;
	
	public function new(name:String, label:String, ?value:String = "Submit", ?type:ButtonType = null) 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.type = (type == null) ? ButtonType.SUBMIT : type; 
	}
	
	override public function isValid():Bool
	{
		return true;
	}
	
	override public function render() :String
	{
		return "<button type=\""+type+"\" name=\"" +form.name+"_" +name+ "\" id=\"" +form.name+"_" +name+ "\" value=\""+value+"\" "+attributes+" >" +label+ "</button>";
	}
	
	public function toString() :String
	{
		return render();
	}
	
	override public function getLabel():String
	{
		var n = form.name + "_" + name;
		
		return "<label for=\"" + n + "\" ></label>";
	}
	
	override public function getPreview():String
	{
		return "<tr><td></td><td>" + this.render() + "<td></tr>";
	}
}

enum ButtonType
{
	SUBMIT;
	BUTTON;
	RESET;
}