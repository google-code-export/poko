/**
 * ...
 * @author Tony Polinelli
 */

package poko.form.validators;
import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;
import poko.utils.StringTools2;

class RegexValidator extends Validator
{
	public var regex:EReg;
	public var regexOptions:String;
	public var errorRegex:String;
	
	public function new(regex:EReg, ?errorMessage:String=null ) 
	{
		super();
		this.regex = regex;
		errorRegex = (errorMessage != null) ? errorMessage : "Regex Failed";
	}
	
	override public function isValid(value:Dynamic):Bool
	{
		super.isValid(value);
		
		var valid:Bool = true;
		
		if (!regex.match(Std.string(value)))
		{
			errors.add(StringTools2.printf(errorRegex, [regex]));
			valid = false;
		}
		
		return valid;
	}
}