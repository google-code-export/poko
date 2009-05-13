/**
 * ...
 * @author Tony Polinelli
 */

package poko.form.validators;
import poko.form.Validator;
import poko.utils.StringTools2;

class NumberValidator extends Validator
{
	public var isInt:Bool;
	public var min:Float;
	public var max:Float;
	
	public var errorNumber:String;
	public var errorInt:String;
	public var errorMin:String;
	public var errorMax:String;
	
	public function new(min:Float=0, max:Float=999999999999, isInt:Bool=false) 
	{
		super();
		
		this.min = min;
		this.max = max;
		this.isInt = isInt;
		
		errorNumber = "Must be a number";
		errorInt = "Must be an integer";
		errorMin = "Minimum number %s";
		errorMax = "Maximum number %s";
	}
	
	override public function isValid(value:Dynamic):Bool
	{
		super.isValid(value);
		
		var valid = true;
		var f = Std.parseFloat(Std.string(value));
		var i = Std.int(f);
		
		if (Math.isNaN(f))
		{
			errors.add(errorNumber);
			valid = false;
		}else{
		
			if (isInt && i != f) {
				errors.add(errorInt);
				valid = false;
			}
			
			var n:Float = isInt ? i : f;
			
			if (n < min) {
				errors.add(StringTools2.printf(errorMin, [min]));
				valid = false;
			}else if (n > max) {
				errors.add(StringTools2.printf(errorMax, [max]));
				valid = false;
			}
		}
		
		return valid;
	}
	
}

/*

	var v:NumberValidator = new NumberValidator();
	v.isInt = false;
	v.min = -5;
	v.max = 10.55;
	trace(v.validate(5));
	trace(v.errors);
	v.reset();
	trace(v.validate( -6));
	trace(v.errors);
	v.reset();
	trace(v.validate( -3));
	trace(v.errors);
	v.reset();
	trace(v.validate(11.5));
	trace(v.errors);
	v.reset();
	
*/