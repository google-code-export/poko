
package poko.form;
import poko.Application;
import poko.utils.JsBinding;
import poko.utils.PhpTools;
import php.Web;

class FormElement
{		
	public var form:Form;
	public var name:String;
	public var label:String;
	public var value:Dynamic;
	public var required:Bool;
	public var errors:List<String>;
	public var attributes:String;
	public var active:Bool;
	public var validators:List<Validator>;
	
	public function new() 
	{
		active = true;
		errors = new List();
		validators = new List();
	}
	
	public function isValid():Bool
	{
		errors.clear();
		
		if (value == "" && required) 
		{
			errors.add("please fill in: '" + name + "'");
			return false;
		} 
		else if(value != "")
		{
			if (!validators.isEmpty())
			{
				var pass:Bool = true;
				for (validator in validators)
				{
					if (!validator.isValid(value)) {
						//for (error in validator.errors)
						//	errors.add(error);
						
						pass = false;
					}
				}
				if (!pass) return false;
			}
			
			return true;
		}
		return true;
	}
	
	public function addValidator(validator:Validator)
	{
		validators.add(validator);
	}
	
	public function bindEvent(event:String, method:String, params:Array<Dynamic>, ?isMethodGlobal:Bool=false) 
	{
		//Application.instance.request.jsBindings.add(new JsBinding(form.name + "_" + name, event, method, params, isMethodGlobal));
	}
	
	public function populate():Void
	{
		var n = form.name + "_" + name;
		var v = Application.instance.params.get(n);
		
		if (v != null) value = v;
	}
	
	public function getErrors():List<String>
	{
		isValid();
		
		for (val in validators)
			for(err in val.errors)
				errors.add(label + " : " + err);
		
		return errors;
	}
	
	public function render():String
	{
		return value;
	}
	
	public function getPreview():String
	{
		return "<tr><td>" + getLabel() + "</td><td>" + this.render() + "<td></tr>";
	}
	
	public function getType():String
	{
		return Std.string(Type.getClass(this));
	}
	public function getLabel():String
	{
		var n = form.name + "_" + name;
		
		return "<label for=\"" + n + "\" >" + label +(if(required) "*") +"</label>";
	}
}
