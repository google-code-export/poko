/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;
import poko.form.Form;
import poko.form.FormElement;

class Selectbox extends FormElement 
{
	public var data:List<Dynamic>;
	public var nullMessage:String;
	public var onChange:String;
	
	public function new(name:String, label:String, ?data:List<Dynamic>, ?selected:String, required:Bool=false, ?nullMessage="- select -") 
	{
		super();
		this.name = name;
		this.label = label;
		this.data = data != null ? data: new List();
		this.value = selected;
		this.required = required;
		this.nullMessage = nullMessage;
		
		onChange = "";
	}
	
	override public function render():String
	{
		var s = "";
		var n = form.name + "_" +name;

		s += "\n<select name=\""+n+"\" id=\""+n+"\" "+attributes+" onChange=\""+onChange+"\" />";
		
		if (nullMessage != "")
			s += "<option value=\"\" " + (Std.string(value) == "" ? "selected":"") + ">" + nullMessage + "</option>";
			
		if (data != null)
		{	
			for (row in data) {
				s += "<option value=\"" + row.value + "\" " + (row.value == Std.string(value) ? "selected":"") + ">" + row.key + "</option>";
			}
		}
		s += "</select>";
	 
		return s;
	}
	
	public function addOption(keyVal:Dynamic)
	{
		data.add(keyVal);
	}
	
	public function toString() :String
	{
		return render();
	}
}