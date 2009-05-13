/**
 * ...
 * @author Tony Polinelli
 */

package poko.form.validators;
import poko.form.Validator;
import poko.utils.StringTools2;
import EReg;

class StringValidator extends Validator
{
	public var minChars:Int;
	public var maxChars:Int;
	public var charList:String;
	public var mode:StringValidatorMode;
	
	public var regex:EReg;
	public var regexError:String;
	
	public var errorMinChars:String;
	public var errorMaxChars:String;
	public var errorDenyChars:String;
	public var errorAllowChars:String;
	
	public function new(?minChars:Int=0, ?maxChars:Int=999999, ?charList:String="", ?mode:StringValidatorMode, ?regex:EReg, ?regexError:String)
	{	
		super();
		
		errorMinChars = "Must be at least %s characters long";
		errorMaxChars = "Must be less than  %s characters long";
		errorDenyChars = "Cannot contain the characters %s";
		errorAllowChars = "Must contain only the characers %s";
	
		this.minChars = minChars;
		this.maxChars = maxChars;
		this.charList = charList;
		this.mode = mode;
		
		this.regex = regex;
		this.regexError = regexError != null ? regexError : "Doesn't match required input.";
		
		errors = new List();
	}
	
	override public function isValid(value:Dynamic):Bool
	{
		super.isValid(value);
		
		var valid = true;
		var s = Std.string(value);
		
		if (minChars != null && minChars > 0 && s.length < minChars) 
		{
			valid = false;
			errors.add(StringTools2.printf(errorMinChars, [ minChars]));
		} 

		if (maxChars != null && maxChars > 0 && s.length > maxChars) 
		{
			valid = false;
			errors.add(StringTools2.printf(errorMaxChars, [maxChars]));
		}
		
		if (charList.length > 0)
		{
			switch(mode)
			{
				case StringValidatorMode.ALLOW:
					for (i in 0...s.length)
					{
						var letter = s.charAt(i);
						if (charList.indexOf(letter) == -1)
						{
							valid = false;
							errors.add(StringTools2.printf(errorAllowChars, [StringTools2.toSentenceList(charList)]));
							break;
						}
					}
				case StringValidatorMode.DENY:
					for (i in 0...s.length)
					{
						var letter = s.charAt(i);
						if (charList.indexOf(letter) != -1)
						{
							valid = false;
							errors.add(StringTools2.printf(errorDenyChars, [StringTools2.toSentenceList(charList)]));
							break;
						}
					}
			}
		}
		
		if (regex != null)
		{
			if (!regex.match(s))
			{
				valid = false;
				errors.add(regexError);
			}
		}
		
		return valid;
	}
	
}


enum StringValidatorMode
{
	ALLOW;
	DENY;
}