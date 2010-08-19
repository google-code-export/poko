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
		
		var data:Dynamic = {};
		daySelector = new Selectbox("", "Birth Day",ListData.getDays(),data.childsBirthDay,true,"Day",'title="Day"');
		monthSelector = new Selectbox("", "Birth Month",ListData.getMonths(),data.childsBirthMonth,true,"Month", 'title="Month"');
		yearSelector = new Selectbox("", "Birth Year", ListData.getYears(1920, 2010, true), data.childsBirthYear, true, "Year", 'title="Year"');
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