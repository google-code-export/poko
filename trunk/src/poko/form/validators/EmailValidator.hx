package poko.form.validators;

import poko.form.Validator;

class EmailValidator extends Validator
{
	public var errorNotValid:String;
	
	public function new()
	{
		super();
		errorNotValid = "Not a valid email address";
	}
	
	override public function isValid(value:Dynamic):Bool
	{
		super.isValid(value);
		
		var valid = emailRegex.match(Std.string(value));
		return valid;
	}

	public static var emailRegex:EReg = ~/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/i;
}