/**
 * ...
 * @author Tonypee
 */

package poko.form.elements;
import php.Web;
import poko.form.FormElement;
import poko.form.Validator;
import poko.Poko;
import poko.utils.ListData;


class DateDropdowns extends FormElement
{
	public var maxOffset:Int;
	public var minOffset:Int;
	
	public var datetime : String;
	
	public var yearMin:Int;
	public var yearMax:Int;
	
	private var daySelector:Selectbox;
	private var monthSelector:Selectbox;
	private var yearSelector:Selectbox;
	
	public function new(name:String, label:String, ?value:Date, ?required:Bool=false, yearMin:Int=1950, yearMax:Int=null, ?validators:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.datetime = Std.string(value);
		this.value = value;
		
		this.required = required;
		this.attributes = attibutes;
		
		this.yearMin = yearMin;
		this.yearMax = yearMax;
		
		maxOffset = null;
		minOffset = null;
		
		var day = "";
		var month = "";
		var year = "";
		
		if (value != null)
		{
			day = ""+ (value.getDate());
			month = ""+ (value.getMonth()+1);
			year = ""+ value.getFullYear();
		}
		
		daySelector = new Selectbox("", "Birth Day",ListData.getDays(),day,true,"-Day-",'title="Day"');
		monthSelector = new Selectbox("", "Birth Month",ListData.getMonths(),month,true,"-Month-", 'title="Month"');
		yearSelector = new Selectbox("", "Birth Year", ListData.getYears(1920, Date.now().getFullYear(), true), year, true, "-Year-", 'title="Year"');
		
		daySelector.internal = monthSelector.internal = yearSelector.internal = true;
	}
	public function shortLabels()
	{
		daySelector.nullMessage = "-D-";
		monthSelector.nullMessage = "-M-";
		yearSelector.nullMessage = "-Y-";
		monthSelector.data = ListData.getMonths(true);
	}
	
	override public function init()
	{
		super.init();
		
		form.addElement(daySelector);
		form.addElement(monthSelector);
		form.addElement(yearSelector);
	}
	
	override public function populate()
	{
		var n = form.name + "_" + name;
		var day = Std.parseInt(Poko.instance.params.get(n + "Day"));
		var month = Std.parseInt(Poko.instance.params.get(n + "Month"));
		var year = Std.parseInt(Poko.instance.params.get(n + "Year"));
		
		value = (day != null && month != null && year != null ) ? new Date(year, month - 1, day, 0, 0, 0) : null;
	}
	
	override public function isValid():Bool
	{
		var valid = super.isValid();
		
		if ( required && valid )
		{
			var n = form.name + "_" + name;
			var day = Std.parseInt(Poko.instance.params.get(n + "Day"));
			var month = Std.parseInt(Poko.instance.params.get(n + "Month"));
			var year = Std.parseInt(Poko.instance.params.get(n + "Year"));
			
			if (day == null || month == null || year == null )
			{
				errors.add("<span class=\"formErrorsField\">" + ((label != null && label != "") ? label : name) + "</span> is an invalid date.");
				return false;
			}
			return true;
		}
		
		return valid;
	}
	
	/*override public function populate()
	{
		var n = form.name + "_" + name;
		var v1 = Poko.instance.params.get(n + "Day");
		var v2 = Poko.instance.params.get(n + "Month");
		var v3 = Poko.instance.params.get(n + "Year");
		
		if (form.isSubmitted())
		{
			value = (v1 != null && v2 != null && v3 != null && v1 != "" && v2 != "" && v3 != "") ?  new Date(v3,Std.parseInt(v2)-1,v1,0,0,0) : Date.now();
		} else {
			if (v1 != null && v2 != null && v3 != null && v1 != "" && v2 != "" && v3 != "") 
				value = new Date(v3,Std.parseInt(v2)-1,v1,0,0,0);
		}
	}*/
	
	public function getValue()
	{
		var n = form.name + "_" + name;
		var day = Std.parseInt(Poko.instance.params.get(n + "Day"));
		var month = Std.parseInt(Poko.instance.params.get(n + "Month"));
		var year = Std.parseInt(Poko.instance.params.get(n + "Year"));
		
		return year + "-" + month + "-" + day;
	}
	
	override public function render():String
	{		
		super.render();
		
		var s = "";

		daySelector.name = name + "Day";
		monthSelector.name = name + "Month";
		yearSelector.name = name + "Year";
		
		if (value != "" && value != null && value != "null")
		{
			var v:Date = cast value;
			daySelector.value = v.getDate();
			monthSelector.value = v.getMonth()+1;
			yearSelector.value = v.getFullYear();
		}
		
		s += daySelector.render();
		s += " / ";
		s += monthSelector.render();
		s += " / ";
		s += yearSelector.render();
		
		return s;
	}
	
	public function toString() :String
	{
		return render();
	}
}