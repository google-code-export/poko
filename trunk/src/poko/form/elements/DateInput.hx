/**
 * ...
 * @author Matt Benton
 */

package poko.form.elements;

import poko.form.FormElement;
import poko.form.Validator;
import poko.Poko;
import poko.utils.ListData;

class DateInput extends DateDropdowns
{	
	private var hourSelector:Selectbox;
	private var minuteSelector:Selectbox;
	//private var secondSelector:Selectbox;
	private var hiddenData : Hidden;
	private var nullBox : Checkbox;
	
	public function new(name:String, label:String, ?value:Date, ?required:Bool=false, yearMin:Int=1950, yearMax:Int=null, ?validators:Array<Validator>, ?attibutes:String="") 
	{
		super(name, label, value, required, yearMin, yearMax, validators, attibutes);
		
		var data : Dynamic = { };
		hourSelector 	= new Selectbox("", "Hour",ListData.getDateElement(0,23), data.childsBirthDay,true,"-",'title="Hour"');
		minuteSelector 	= new Selectbox("", "Minute",ListData.getDateElement(0,59), data.childsBirthMonth,true,"-", 'title="Minute"');
		//secondSelector 	= new Selectbox("", "Second", ListData.getDateElement(0, 59), data.childsBirthYear, true, "-", 'title="Second"');
		hiddenData		= new Hidden("", value, false);
		nullBox			= new Checkbox("", "Null", false, false);
	}
	
	override public function init()
	{
		super.init();
		
		form.addElement(hourSelector);
		form.addElement(minuteSelector);
		//form.addElement(secondSelector);
		form.addElement(hiddenData);
		form.addElement(nullBox);
	}
	
	override public function isValid():Bool
	{
		var valid = super.isValid();
		
		
		
		return valid;
	}
	
	override public function render():String
	{
		var s = super.render() + " : ";

		hourSelector.name = name + "Hour";
		minuteSelector.name = name + "Minute";
		//secondSelector.name = name + "Second";
		hiddenData.name = name;
		nullBox.name = name + "Null";
		
		if (value != "" && value != null && value != "null")
		{
			var v:Date = cast value;
			hourSelector.value = v.getHours();
			minuteSelector.value = v.getMinutes();
			//secondSelector.value = v.getSeconds();
		}
		
		hiddenData.value = value;
		
		//nullBox.value = Poko.instance.params.exists(form.name + "_" + name + "Null") ? "1" : "0";
		
		s += hourSelector.render() + "h ";
		s += minuteSelector.render() + "m ";
		//s += secondSelector.render() + "s";
		
		s += nullBox.render() + " Clear?";
		s += hiddenData.render();
		
		return s;	
	}
	
	override public function populate()
	{			
		var n = form.name + "_" + name;
		
		var makeNull = Poko.instance.params.exists(form.name + "_" + name + "Null");
		
		if ( !makeNull )
		{
			//var second = Std.parseInt(Poko.instance.params.get(n + "Second"));
			var minute = Std.parseInt(Poko.instance.params.get(n + "Minute"));
			var hour = Std.parseInt(Poko.instance.params.get(n + "Hour"));
			var day = Std.parseInt(Poko.instance.params.get(n + "Day"));
			var month = Std.parseInt(Poko.instance.params.get(n + "Month"));
			var year = Std.parseInt(Poko.instance.params.get(n + "Year"));
			
			//if (second != null && minute != null && hour != null && day != null && month != null && year != null) 
			if (minute != null && hour != null && day != null && month != null && year != null) 
			{
				//value = new Date(year, month - 1, day, hour, minute, second);
				value = new Date(year, month - 1, day, hour, minute, 0); 
			}
			else
			{
				var hidden = Poko.instance.params.exists(n) ? Poko.instance.params.get(n) : value;
				value = (hidden != null && hidden != "") ? Date.fromString(hidden) : null;
			}
		}
		else
		{
			value = null;
		}
	}
}

/*class DateInput extends FormElement
{
	static inline var EREG_WS : String = "\\s*";
	static inline var EREG_SLASH : String = EREG_WS + "/" + EREG_WS;
	static inline var EREG_DATE : String = "^" + EREG_WS + "(\\d\\d)" + EREG_SLASH + "(\\d\\d)" + EREG_SLASH + "(\\d\\d\\d\\d)" + EREG_WS + "$";
	
	public var format : EReg;
	
	public function new( name : String, label : String, ?dateStr : String ) 
	{
		super();
		this.name = name;
		this.label = label;
		
		this.value = dateStr;

		format = new EReg(EREG_DATE, null);
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		
		var s:StringBuf = new StringBuf();
		
		s.add('<input type="text" name="' + n + '" id="' + n + '" value="' + value + '" />\n');
		
		return s.toString();
	}
	
	//function createDigitInput
}*/