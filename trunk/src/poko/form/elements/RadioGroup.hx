/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;
import poko.form.Form;
import poko.form.FormElement;

class RadioGroup extends FormElement 
{
	public var data:List<Dynamic>;
	public var selectMessage:String;
	public var labelLeft:Bool;
	public var verticle:Bool;
	public var labelRight:Bool;
	
	public function new(name:String, label:String, data:List<Dynamic>, ?selected:String, defaultValue:String, ?verticle:Bool=true, ?labelRight:Bool=true) 
	{
		super();
		this.name = name;
		this.label = label;
		this.data = data;
		this.value = selected != null ? selected : defaultValue;
		this.verticle = verticle;
		this.labelRight = labelRight;
	}
	
	override public function render():String
	{
		var s = "";
		var n = form.name + "_" +name;
		
		var c = 0;
		if (data != null)
		{
			for (row in data)
			{
				var radio = "<input type=\"radio\" name=\""+n+"\" id=\""+n+c+"\" value=\"" + row.value + "\" " + (row.value == Std.string(value) ? "checked":"") +" />\n";
				var label = "<label for=\"" + n+c + "\" >" + row.key  +"</label>";
				
				s += labelRight ? radio + " "+label+" ": label+" "+radio+" ";
				if (verticle) s += "<br />";
				c++;
			}	
		}
		
		return s;
	}
	
	public function toString() :String
	{
		return render();
	}
}