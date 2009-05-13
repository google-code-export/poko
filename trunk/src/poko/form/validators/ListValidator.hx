/**
 * ...
 * @author Tony Polinelli
 */

package poko.form.validators;

import poko.form.Validator;
import poko.utils.StringTools2;

class ListValidator extends Validator
{
	public var list:Array<Dynamic>;
	public var mode:ListValidatorMode;
	
	public var errorAllow:String;
	public var errorDeny:String;
	
	public function new(?mode:ListValidatorMode)
	{
		super();
		
		errorAllow = "Only the values %s are allowed.";
		// this is used for the complete list of denied values
		//errorDeny = "The values %s are not allowed.";
		errorDeny = "The value '%s' is not allowed.";
		
		this.mode = mode != null ? mode : ListValidatorMode.ALLOW;
	}
	
	override public function isValid(value:Dynamic):Bool
	{
		super.isValid(value);
		
		var valueExists = Lambda.has(list, value);
		var valid = (mode == ListValidatorMode.ALLOW) ? valueExists : !valueExists;
		if (!valid) {
			// this one returns a list of denied values, which is nice, but though thought it might be a security risk somehow?
			//errors.push(StringTools2.printf(mode == ListValidatorMode.ALLOW ? errorAllow : errorDeny, [joinAsSentence(list, "'")]));
			if (mode == ListValidatorMode.ALLOW) {
				errors.push(StringTools2.printf(errorAllow, [joinAsSentence(list, "'")]));
			}else {
				errors.push(StringTools2.printf(errorDeny, [value]));
			}
		}
		return valid;
	}
	
	private function joinAsSentence(a:Array<Dynamic>, ?wrapWith:String):String
	{
		if (wrapWith != null) {
			for(i in 0...a.length)
				a[i] = wrapWith + a[i] + wrapWith;
		}
		var e = a.pop();
		var s = a.join(", ") + " and " + e;
		return s;
	}
}

enum ListValidatorMode
{
	ALLOW;
	DENY;
}

/*
	var v:ListValidator = new ListValidator(ListValidatorMode.ALLOW);
	var a:Array<Dynamic> = ["good", "bad", "stupid"];
	v.list = a;
	trace(v.isValid("good"));
	trace(v.errors);
	v.reset();
	v.list = a;
	trace(v.isValid("bad"));
	trace(v.errors);
	v.reset();
	v.list = a;
	trace(v.isValid("ugly"));
	trace(v.errors);
	v.reset();
*/