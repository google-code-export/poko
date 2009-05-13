/** 
 * poko haxe goodness
 * @author Tony Polinelli <tonyp@touchmypixel.com>
 */

package poko.form.elements;
import poko.form.Form;
import poko.form.FormElement;
import php.Web;

class CheckboxGroup extends FormElement 
{
	public var data:List<Dynamic>;
	public var selectMessage:String;
	public var labelLeft:Bool;
	public var verticle:Bool;
	public var labelRight:Bool;
	
	public function new(name:String, label:String, data:List<Dynamic>, ?selected:Array<String>, ?verticle:Bool=true, ?labelRight:Bool=true) 
	{
		super();
		this.name = name;
		this.label = label;
		this.data = data;
		this.value = selected != null ? selected : new Array();
		this.verticle = verticle;
		this.labelRight = labelRight;
	}
	
	override public function populate()
	{
		
		var v = Web.getParamValues(form.name + "_" + name);
		
		if (form.isSubmitted())
		{
			value = (v != null) ? v : new Array();
		} else {
			if (v != null) 
				value = v;
		}
	}
	
	override public function render():String
	{
		var s = "";
		var n = form.name + "_" +name;
		
		if (value != null)
		{
			value = Lambda.map(value, function(item) {
				return item+"";
			});
		}
		
		var c = 0;
		if (data != null)
		{
			for (row in data)
			{
				var checkbox = "<input type=\"checkbox\" name=\""+n+"[]\" id=\""+n+c+"\" value=\"" + row.key + "\" " + (value != null ? Lambda.has(value, row.key+"") ? "checked":"":"") +" ></input>\n";
				var label = "<label for=\"" + n+c + "\" >" + row.value  +"</label>";
				
				s += labelRight ? checkbox + " "+label+" ": label+" "+checkbox+" ";
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